<?php

namespace App\Http\Controllers;

use App\Services\AdaptiveDetectionService;
use App\Models\Gejala;
use App\Models\DiagnosisHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeteksiController extends Controller
{
    protected $detectionService;

    public function __construct(AdaptiveDetectionService $detectionService)
    {
        $this->detectionService = $detectionService;
    }

    public function index()
    {
        return view('deteksi.index');
    }

    public function start(Request $request)
    {
        $request->session()->forget(['selected_symptoms', 'skipped_symptoms']);
        return redirect()->route('deteksi.wizard');
    }

    public function wizard(Request $request)
    {
        $selected = session('selected_symptoms', []);
        $symptoms = Gejala::orderBy('kode_gejala')->get();
        $selectedCount = count(array_filter($selected, fn($val) => $val > 0));

        return view('deteksi.wizard', compact('symptoms', 'selected', 'selectedCount'));
    }

    public function processStep(Request $request)
    {
        $validated = $request->validate([
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'nullable|numeric|min:0.1|max:1.0',
        ]);

        $inputSymptoms = $request->input('symptoms', []);
        $selected = [];

        foreach ($inputSymptoms as $id => $cf) {
            $cfVal = (float)$cf;
            if ($cfVal > 0) {
                $selected[$id] = round(min(max($cfVal, 0.1), 1.0), 1);
            }
        }

        $selectedCount = count($selected);
        if ($selectedCount < 1 || $selectedCount > 3) {
            return redirect()
                ->route('deteksi.wizard')
                ->withErrors(['symptoms' => 'Pilih minimal 1 dan maksimal 3 gejala tanaman untuk diagnosis.'])
                ->withInput();
        }

        session(['selected_symptoms' => $selected]);

        return redirect()->route('deteksi.result');
    }

    public function nextSymptoms(Request $request)
    {
        return redirect()->route('deteksi.wizard');
    }

    public function result(Request $request)
    {
        $selected = session('selected_symptoms', []);
        $selectedCount = count(array_filter($selected, fn($val) => $val > 0));

        if ($selectedCount < 1 || $selectedCount > 3) {
            return redirect()->route('deteksi.wizard');
        }

        $results = $this->detectionService->diagnose($selected, Auth::id());

        return view('deteksi.result', compact('results', 'selected'));
    }

    public function reset(Request $request)
    {
        $request->session()->forget(['selected_symptoms', 'skipped_symptoms']);
        return redirect()->route('beranda');
    }

    public function history()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $histories = DiagnosisHistory::withTrashed()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $histories = DiagnosisHistory::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }

        return view('deteksi.history', compact('histories'));
    }

    public function deleteHistory(Request $request)
    {
        $request->validate([
            'history_ids' => 'required|array|min:1',
            'history_ids.*' => 'integer|exists:diagnosis_histories,id',
        ]);

        $query = DiagnosisHistory::whereIn('id', $request->history_ids);

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $deletedCount = $query->delete();

        return redirect()
            ->route('deteksi.history')
            ->with('success', $deletedCount . ' riwayat diagnosa disembunyikan dari daftar.');
    }
}
