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
        $skipped = session('skipped_symptoms', []);
        
        $selectedCount = count(array_filter($selected, fn($val) => $val > 0));

        if ($selectedCount >= 3) {
            return redirect()->route('deteksi.result');
        }

        if (empty($selected)) {
            $symptoms = $this->detectionService->getInitialSymptoms($skipped);
        } else {
            $symptoms = $this->detectionService->getNextSymptoms($selected, $skipped);
            
            if ($symptoms->isEmpty() && $selectedCount >= 1) {
                return redirect()->route('deteksi.result');
            }
        }

        return view('deteksi.wizard', compact('symptoms', 'selected', 'selectedCount'));
    }

    public function processStep(Request $request)
    {
        $selected = session('selected_symptoms', []);
        $inputSymptoms = $request->input('symptoms', []);

        foreach ($inputSymptoms as $id => $cf) {
            $cfVal = (float)$cf;
            if ($cfVal > 0) {
                $selected[$id] = $cfVal;
            }
        }

        session(['selected_symptoms' => $selected]);

        if ($request->has('diagnose') || count(array_filter($selected, fn($val) => $val > 0)) >= 3) {
            return redirect()->route('deteksi.result');
        }

        return redirect()->route('deteksi.wizard');
    }

    public function nextSymptoms(Request $request)
    {
        $skipped = session('skipped_symptoms', []);
        $currentIds = $request->input('current_ids', []);

        foreach ($currentIds as $id) {
            if (!in_array($id, $skipped)) {
                $skipped[] = (int)$id;
            }
        }

        session(['skipped_symptoms' => $skipped]);
        return redirect()->route('deteksi.wizard');
    }

    public function result(Request $request)
    {
        $selected = session('selected_symptoms', []);
        $selectedCount = count(array_filter($selected, fn($val) => $val > 0));

        if ($selectedCount === 0) {
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
            $histories = DiagnosisHistory::with('user')->orderBy('created_at', 'desc')->get();
        } else {
            $histories = DiagnosisHistory::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }

        return view('deteksi.history', compact('histories'));
    }
}
