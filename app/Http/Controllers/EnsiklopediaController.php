<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\Gejala;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsiklopediaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type'); // 'penyakit' or 'hama'

        $query = Library::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nama_latin', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($type === 'penyakit') {
            $query->where('jenis', 'penyakit');
        } elseif ($type === 'hama') {
            $query->where('jenis', 'hama');
        }

        $items = $query->orderBy('nama')->get();
        $count = $items->count();

        return view('ensiklopedia.index', compact('items', 'search', 'type', 'count'));
    }

    public function show($slug)
    {
        // Primary: match by Library.nama slug
        $all = Library::all();
        $item = $all->first(fn($i) => Str::slug($i->nama) === $slug);

        // Fallback: if not found, try matching via Penyakit/Hama slug columns
        if (!$item) {
            $penyakit = \App\Models\Penyakit::where('slug', $slug)->first();
            if ($penyakit) {
                $item = Library::where('jenis', 'penyakit')->where('nama', $penyakit->nama_penyakit)->first();
            }
        }
        if (!$item) {
            $hama = \App\Models\Hama::where('slug', $slug)->first();
            if ($hama) {
                $item = Library::where('jenis', 'hama')->where('nama', $hama->nama_hama)->first();
            }
        }

        if (!$item) {
            abort(404);
        }

        // Find symptoms associated with this library item via the rule mapping in Basis Pengetahuan
        $targetType = $item->jenis; // 'penyakit' or 'hama'
        
        // Find corresponding disease/pest code in basis pengetahuan target table
        if ($targetType === 'penyakit') {
            $target = \App\Models\Penyakit::where('nama_penyakit', $item->nama)->first();
        } else {
            $target = \App\Models\Hama::where('nama_hama', $item->nama)->first();
        }

        $symptoms = collect();
        if ($target) {
            $symptomIds = Rule::where('target_type', $targetType)
                ->where('target_id', $target->id)
                ->pluck('gejala_id');
            $symptoms = Gejala::whereIn('id', $symptomIds)->get();
        }

        return view('ensiklopedia.show', compact('item', 'symptoms'));
    }

    /**
     * Return JSON search suggestions for live autocomplete.
     */
    public function suggestions(Request $request)
    {
        $q = $request->input('q', '');
        
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $items = Library::where('nama', 'like', "%{$q}%")
            ->orWhere('nama_latin', 'like', "%{$q}%")
            ->orderBy('nama')
            ->limit(8)
            ->get(['nama', 'jenis', 'nama_latin']);

        $suggestions = $items->map(fn($item) => [
            'nama' => $item->nama,
            'jenis' => $item->jenis,
            'nama_latin' => $item->nama_latin,
            'slug' => Str::slug($item->nama),
        ]);

        return response()->json($suggestions);
    }
}
