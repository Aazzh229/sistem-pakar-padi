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
            ->limit(1)
            ->get();

        $ids = $symptomsData->pluck('gejala_id')->toArray();
        return Gejala::whereIn('id', $ids)
            ->get()
            ->sortBy(fn($g) => array_search($g->id, $ids));
    }

    /**
     * Round 2: Get exactly 1 other symptom per candidate target (disease/hama).
     */
    public function getNextSymptomsRound2(array $selected, array $skipped = [])
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

        $symptomIds = [];
        $excludeIds = array_merge($selectedIds, $skipped);

        // For each candidate target, select exactly 1 other symptom (e.g., highest pakar CF)
        foreach ($candidateTargets as $candidate) {
            $otherSymptom = Rule::where('target_type', $candidate->target_type)
                ->where('target_id', $candidate->target_id)
                ->whereNotIn('gejala_id', array_merge($excludeIds, $symptomIds))
                ->orderBy('cf_pakar', 'desc')
                ->first();

            if ($otherSymptom) {
                $symptomIds[] = $otherSymptom->gejala_id;
            }
        }

        // Return only the first symptom to show one per round
        $uniqueIds = array_unique($symptomIds);
        $firstId = reset($uniqueIds);
        return $firstId ? Gejala::where('id', $firstId)->get() : collect();
    }

    /**
     * Round 3: Get all remaining symptoms for all candidate targets.
     */
    public function getNextSymptomsRound3(array $selected, array $skipped = [])
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

        $query = Rule::query();
        $query->where(function($q) use ($candidateTargets) {
            foreach ($candidateTargets as $candidate) {
                $q->orWhere(function($sub) use ($candidate) {
                    $sub->where('target_type', $candidate->target_type)
                        ->where('target_id', $candidate->target_id);
                });
            }
        });

        $excludeIds = array_merge($selectedIds, $skipped);
        $symptomsData = $query->select('gejala_id')
            ->whereNotIn('gejala_id', $excludeIds)
            ->groupBy('gejala_id')
            ->get();

        $ids = $symptomsData->pluck('gejala_id')->toArray();
        // Return only the first symptom
        $firstId = reset($ids);
        return $firstId ? Gejala::where('id', $firstId)->get() : collect();
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

            // Use Library name slug for ensiklopedia link (Library.nama may differ from Penyakit/Hama name)
            $ensiklopediaSlug = $library ? Str::slug($library->nama) : $data['slug'];

            $results[] = [
                'name' => $data['name'],
                'slug' => $ensiklopediaSlug,
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
