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
        $inputSymptoms = $request->input('symptoms', []);
        $selected = [];

        foreach ($inputSymptoms as $id => $cf) {
            $cfVal = (float)$cf;
            if ($cfVal > 0) {
                $selected[$id] = $cfVal;
            }
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
