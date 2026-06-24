<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use App\Models\Hama;
use App\Models\Gejala;
use App\Models\Library;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $penyakitCount = max(5, Penyakit::count());
        $hamaCount = max(5, Hama::count());
        $gejalaCount = max(33, Gejala::count());

        // Fetch disease educational articles from library (up to 2 random/first entries)
        $penyakitList = Library::where('jenis', 'penyakit')
            ->orderBy('nama')
            ->take(2)
            ->get();

        // Fetch pest educational articles from library (up to 2 random/first entries)
        $hamaList = Library::where('jenis', 'hama')
            ->orderBy('nama')
            ->take(2)
            ->get();

        return view('beranda', compact(
            'penyakitCount',
            'hamaCount',
            'gejalaCount',
            'penyakitList',
            'hamaList'
        ));
    }
}
