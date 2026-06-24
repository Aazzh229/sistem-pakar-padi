<?php

namespace App\Services;

use App\Models\Gejala;
use App\Models\Rule;
use App\Models\Library;
use App\Models\DiagnosisHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdaptiveDetectionService
{
    /**
     * Get initial symptoms based on rules frequency and pakar CF.
     */
    public function getInitialSymptoms(array $skipped = [])
    {
        $symptomsData = Rule::select('gejala_id', DB::raw('count(*) as freq'), DB::raw('max(cf_pakar) as max_cf'))
            ->whereNotIn('gejala_id', $skipped)
            ->groupBy('gejala_id')
            ->orderBy('freq', 'desc')
            ->orderBy('max_cf', 'desc')
            ->limit(4)
            ->get();

        $ids = $symptomsData->pluck('gejala_id')->toArray();
        return Gejala::whereIn('id', $ids)
            ->get()
            ->sortBy(fn($g) => array_search($g->id, $ids));
    }

    /**
     * Get next symptoms based on current candidate targets.
     */
    public function getNextSymptoms(array $selected, array $skipped = [])
    {
        $selectedIds = array_keys(array_filter($selected, fn($val) => $val > 0));

        if (empty($selectedIds)) {
            return $this->getInitialSymptoms($skipped);
        }

        // Get candidate targets matching currently selected symptoms
        $candidateTargets = Rule::select('target_type', 'target_id')
            ->whereIn('gejala_id', $selectedIds)
            ->groupBy('target_type', 'target_id')
            ->get();

        if ($candidateTargets->isEmpty()) {
            return collect();
        }

        // Build a query matching any of the candidate targets
        $query = Rule::query();
        $query->where(function($q) use ($candidateTargets) {
            foreach ($candidateTargets as $candidate) {
                $q->orWhere(function($sub) use ($candidate) {
                    $sub->where('target_type', $candidate->target_type)
                        ->where('target_id', $candidate->target_id);
                });
            }
        });

        // Find remaining symptoms for these candidate targets
        $symptomsData = $query->select('gejala_id', DB::raw('count(*) as freq'), DB::raw('max(cf_pakar) as max_cf'))
            ->whereNotIn('gejala_id', array_merge($selectedIds, $skipped))
            ->groupBy('gejala_id')
            ->orderBy('freq', 'desc')
            ->orderBy('max_cf', 'desc')
            ->limit(4)
            ->get();

        $ids = $symptomsData->pluck('gejala_id')->toArray();
        return Gejala::whereIn('id', $ids)
            ->get()
            ->sortBy(fn($g) => array_search($g->id, $ids));
    }

    /**
     * Diagnose selected symptoms and store in history.
     */
    public function diagnose(array $selected, int $userId)
    {
        $selectedInputs = array_filter($selected, fn($val) => $val > 0);
        
        if (empty($selectedInputs)) {
            return [];
        }

        // Fetch all rules with their symptoms and polymorphic targets
        $rules = Rule::with(['gejala', 'target'])->get();
        $targets = [];

        foreach ($rules as $rule) {
            $target = $rule->target;
            if (!$target) continue;

            $key = $rule->target_type . '_' . $rule->target_id;

            if (isset($selectedInputs[$rule->gejala_id])) {
                $cfUser = $selectedInputs[$rule->gejala_id];
                $cfPakar = (float)$rule->cf_pakar;
                $cfSymptom = $cfUser * $cfPakar;

                if (!isset($targets[$key])) {
                    $targets[$key] = [
                        'target_type' => $rule->target_type,
                        'target_id' => $rule->target_id,
                        'name' => $rule->target_type === 'penyakit' ? $target->nama_penyakit : $target->nama_hama,
                        'slug' => $target->slug,
                        'cf_values' => [],
                        'matched_symptoms' => []
                    ];
                }

                $targets[$key]['cf_values'][] = $cfSymptom;
                $targets[$key]['matched_symptoms'][] = [
                    'kode' => $rule->gejala->kode_gejala,
                    'nama' => $rule->gejala->nama_gejala
                ];
            }
        }

        $results = [];

        foreach ($targets as $key => $data) {
            // Combine CF values
            $cfCombine = 0.0;
            $first = true;
            foreach ($data['cf_values'] as $val) {
                if ($first) {
                    $cfCombine = $val;
                    $first = false;
                } else {
                    $cfCombine = $cfCombine + $val * (1.0 - $cfCombine);
                }
            }

            // Fetch library article for solutions / details
            $library = Library::where('jenis', $data['target_type'])
                ->where('nama', $data['name'])
                ->first();

            $results[] = [
                'name' => $data['name'],
                'slug' => $data['slug'],
                'type' => $data['target_type'],
                'cf' => $cfCombine,
                'percentage' => round($cfCombine * 100, 2),
                'matched_symptoms' => $data['matched_symptoms'],
                'description' => $library ? Str::limit($library->deskripsi, 120) : 'Tidak ada deskripsi.',
                'solusi' => $library ? Str::limit($library->solusi, 120) : 'Konsultasikan dengan pakar.'
            ];
        }

        // Sort by Certainty Factor descending
        usort($results, fn($a, $b) => $b['cf'] <=> $a['cf']);

        // Save history in database
        $highestPercentage = !empty($results) ? $results[0]['percentage'] : 0.0;
        DiagnosisHistory::create([
            'user_id' => $userId,
            'hasil' => $results,
            'persentase' => $highestPercentage
        ]);

        return $results;
    }
}
