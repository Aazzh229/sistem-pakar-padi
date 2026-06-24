<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Hama;
use App\Models\Rule;
use App\Models\Library;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PakarController extends Controller
{
    public function dashboard()
    {
        return view('pakar.dashboard');
    }

    public function showInputRules()
    {
        $gejala = Gejala::orderBy('kode_gejala')->get();
        $penyakit = Penyakit::orderBy('nama_penyakit')->get();
        $hama = Hama::orderBy('nama_hama')->get();

        return view('pakar.rules.create', compact('gejala', 'penyakit', 'hama'));
    }

    public function storeRules(Request $request)
    {
        $request->validate([
            'target_type'    => 'required|in:penyakit,hama',
            'target_id'      => 'required|integer',
            'gejala_ids'     => 'required|array|min:1|max:3',
            'gejala_ids.*'   => 'required|exists:gejala,id',
            'cf_pakar_list'  => 'required|array',
            'cf_pakar_list.*'=> 'required|numeric|between:0.1,1.0',
        ]);

        $targetType  = $request->input('target_type');
        $targetId    = $request->input('target_id');
        $gejalaIds   = $request->input('gejala_ids');
        $cfValues    = $request->input('cf_pakar_list');

        foreach ($gejalaIds as $index => $gId) {
            $cf = (float)($cfValues[$index] ?? 0.8);

            // Skip duplicate rules
            $exists = Rule::where('gejala_id', $gId)
                ->where('target_type', $targetType)
                ->where('target_id', $targetId)
                ->exists();

            if (!$exists) {
                Rule::create([
                    'gejala_id'   => $gId,
                    'target_type' => $targetType,
                    'target_id'   => $targetId,
                    'cf_pakar'    => $cf,
                    'created_by'  => Auth::id()
                ]);
            }
        }

        return redirect()->route('pakar.dashboard')->with('success', 'Basis pengetahuan berhasil ditambahkan.');
    }


    public function showInputLibrary()
    {
        return view('pakar.library.create');
    }

    public function storeLibrary(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:penyakit,hama',
            'nama' => 'required|string|max:255',
            'nama_latin' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'penyebab' => 'nullable|string',
            'solusi' => 'required|string',
            'pencegahan' => 'nullable|string',
            'gambar' => 'nullable|url',
        ]);

        Library::create(array_merge(
            $request->all(),
            ['created_by' => Auth::id()]
        ));

        return redirect()->route('pakar.dashboard')->with('success', 'Artikel library berhasil ditambahkan.');
    }

    public function showCreateUser()
    {
        return view('pakar.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'active',
            'created_by' => Auth::id()
        ]);

        return redirect()->route('pakar.dashboard')->with('success', 'Akun Petani berhasil dibuat.');
    }

    public function showInputGejala()
    {
        // Sugest kode gejala
        $lastGejala = Gejala::orderBy('kode_gejala', 'desc')->first();
        $suggestedCode = 'G01';
        if ($lastGejala && preg_match('/G(\d+)/', $lastGejala->kode_gejala, $matches)) {
            $num = (int)$matches[1] + 1;
            $suggestedCode = 'G' . str_pad($num, 2, '0', STR_PAD_LEFT);
        }
        return view('pakar.gejala.create', compact('suggestedCode'));
    }

    public function storeGejala(Request $request)
    {
        $request->validate([
            'kode_gejala' => 'required|string|unique:gejala,kode_gejala',
            'nama_gejala' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Gejala::create([
            'kode_gejala' => strtoupper($request->kode_gejala),
            'nama_gejala' => $request->nama_gejala,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('pakar.dashboard')->with('success', 'Gejala Baru berhasil ditambahkan.');
    }

    public function showInputPenyakit()
    {
        // Sugest kode penyakit
        $lastPenyakit = Penyakit::orderBy('kode_penyakit', 'desc')->first();
        $suggestedCode = 'P01';
        if ($lastPenyakit && preg_match('/P(\d+)/', $lastPenyakit->kode_penyakit, $matches)) {
            $num = (int)$matches[1] + 1;
            $suggestedCode = 'P' . str_pad($num, 2, '0', STR_PAD_LEFT);
        }
        return view('pakar.penyakit.create', compact('suggestedCode'));
    }

    public function storePenyakit(Request $request)
    {
        $request->validate([
            'kode_penyakit' => 'required|string|unique:penyakit,kode_penyakit',
            'nama_penyakit' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Penyakit::create([
            'kode_penyakit' => strtoupper($request->kode_penyakit),
            'nama_penyakit' => $request->nama_penyakit,
            'slug' => Str::slug($request->nama_penyakit),
            'deskripsi' => $request->deskripsi,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('pakar.dashboard')->with('success', 'Penyakit Baru berhasil ditambahkan.');
    }

    public function showInputHama()
    {
        // Sugest kode hama
        $lastHama = Hama::orderBy('kode_hama', 'desc')->first();
        $suggestedCode = 'H01';
        if ($lastHama && preg_match('/H(\d+)/', $lastHama->kode_hama, $matches)) {
            $num = (int)$matches[1] + 1;
            $suggestedCode = 'H' . str_pad($num, 2, '0', STR_PAD_LEFT);
        }
        return view('pakar.hama.create', compact('suggestedCode'));
    }

    public function storeHama(Request $request)
    {
        $request->validate([
            'kode_hama' => 'required|string|unique:hama,kode_hama',
            'nama_hama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Hama::create([
            'kode_hama' => strtoupper($request->kode_hama),
            'nama_hama' => $request->nama_hama,
            'slug' => Str::slug($request->nama_hama),
            'deskripsi' => $request->deskripsi,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('pakar.dashboard')->with('success', 'Hama Baru berhasil ditambahkan.');
    }
}

