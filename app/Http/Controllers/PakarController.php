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
    public function indexRules()
{
    $rules = Rule::with(['gejala', 'target'])->get();
    $gejala = Gejala::all();
    $penyakit = Penyakit::all();
    $hama = Hama::all();

    return view('pakar.rules.index', compact(
        'rules',
        'gejala',
        'penyakit',
        'hama'
    ));
}

public function indexLibrary()
{
    $libraries = Library::orderBy('nama')->get();

    return view('pakar.library.index', compact('libraries'));
}
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
            'target_type'  => 'required|in:penyakit,hama',
            'symptom_rows' => 'required|array|min:1|max:3',
        ]);

        $targetType = $request->input('target_type');
        $isNewTarget = $request->boolean('is_new_target');

        if ($isNewTarget) {
            $newTargetName = trim($request->input('new_target_name'));
            if (empty($newTargetName)) {
                return back()->withErrors(['new_target_name' => 'Nama target baru harus diisi jika Anda memilih opsi target baru.'])->withInput();
            }

            if ($targetType === 'penyakit') {
                $target = Penyakit::where('nama_penyakit', $newTargetName)->first();
                if (!$target) {
                    $last = Penyakit::orderBy('kode_penyakit', 'desc')->first();
                    $suggestedCode = 'P01';
                    if ($last && preg_match('/P(\d+)/', $last->kode_penyakit, $matches)) {
                        $num = (int)$matches[1] + 1;
                        $suggestedCode = 'P' . str_pad($num, 2, '0', STR_PAD_LEFT);
                    }
                    $target = Penyakit::create([
                        'kode_penyakit' => $suggestedCode,
                        'nama_penyakit' => $newTargetName,
                        'slug' => Str::slug($newTargetName),
                        'created_by' => Auth::id()
                    ]);
                }
            } else {
                $target = Hama::where('nama_hama', $newTargetName)->first();
                if (!$target) {
                    $last = Hama::orderBy('kode_hama', 'desc')->first();
                    $suggestedCode = 'H01';
                    if ($last && preg_match('/H(\d+)/', $last->kode_hama, $matches)) {
                        $num = (int)$matches[1] + 1;
                        $suggestedCode = 'H' . str_pad($num, 2, '0', STR_PAD_LEFT);
                    }
                    $target = Hama::create([
                        'kode_hama' => $suggestedCode,
                        'nama_hama' => $newTargetName,
                        'slug' => Str::slug($newTargetName),
                        'created_by' => Auth::id()
                    ]);
                }
            }
            $targetId = $target->id;

            // Auto-sync with Library so it appears in the encyclopedia
            $libExists = Library::where('jenis', $targetType)->where('nama', $newTargetName)->exists();
            if (!$libExists) {
                Library::create([
                    'jenis' => $targetType,
                    'nama' => $newTargetName,
                    'deskripsi' => 'Deskripsi penyakit/hama padi ' . $newTargetName . '.',
                    'penyebab' => 'Penyebab belum ditentukan.',
                    'solusi' => 'Hubungi pakar pertanian setempat untuk konsultasi.',
                    'pencegahan' => 'Lakukan monitoring tanaman padi secara berkala.',
                    'created_by' => Auth::id()
                ]);
            }
        } else {
            $targetId = $request->input('target_id');
            if (empty($targetId)) {
                return back()->withErrors(['target_id' => 'Silakan pilih target penyakit atau hama.'])->withInput();
            }
        }

        $symptomRows = $request->input('symptom_rows', []);
        
        foreach ($symptomRows as $row) {
            $isNewGejala = isset($row['is_new']) && $row['is_new'] == '1';
            $cf = (float)($row['cf'] ?? 0.8);

            if ($isNewGejala) {
                $newGejalaName = trim($row['new_name'] ?? '');
                if (empty($newGejalaName)) {
                    continue;
                }

                $gejalaItem = Gejala::where('nama_gejala', $newGejalaName)->first();
                if (!$gejalaItem) {
                    $last = Gejala::orderBy('kode_gejala', 'desc')->first();
                    $suggestedCode = 'G01';
                    if ($last && preg_match('/G(\d+)/', $last->kode_gejala, $matches)) {
                        $num = (int)$matches[1] + 1;
                        $suggestedCode = 'G' . str_pad($num, 2, '0', STR_PAD_LEFT);
                    }
                    $gejalaItem = Gejala::create([
                        'kode_gejala' => $suggestedCode,
                        'nama_gejala' => $newGejalaName,
                        'kategori' => 'Seluruh Tanaman',
                        'created_by' => Auth::id()
                    ]);
                }
                $gejalaId = $gejalaItem->id;
            } else {
                $gejalaId = $row['gejala_id'] ?? null;
            }

            if (!$gejalaId) continue;

            // Link in Rule details
            $exists = Rule::where('gejala_id', $gejalaId)
                ->where('target_type', $targetType)
                ->where('target_id', $targetId)
                ->exists();

            if (!$exists) {
                Rule::create([
                    'gejala_id' => $gejalaId,
                    'target_type' => $targetType,
                    'target_id' => $targetId,
                    'cf_pakar' => $cf,
                    'created_by' => Auth::id()
                ]);
            }
        }

        return redirect()->route('pakar.dashboard')->with('success', 'Basis pengetahuan (Rule) dan item baru berhasil ditambahkan.');
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

    public function showInputMaster(Request $request)
    {
        // Determine default type based on route name or query param
        $defaultType = 'penyakit';
        $routeName = $request->route()->getName();
        if ($routeName === 'pakar.gejala.create') {
            $defaultType = 'gejala';
        } elseif ($routeName === 'pakar.hama.create') {
            $defaultType = 'hama';
        }

        // 1. Suggest code for Penyakit
        $lastPenyakit = Penyakit::orderBy('kode_penyakit', 'desc')->first();
        $suggestedPenyakit = 'P01';
        if ($lastPenyakit && preg_match('/P(\d+)/', $lastPenyakit->kode_penyakit, $matches)) {
            $num = (int)$matches[1] + 1;
            $suggestedPenyakit = 'P' . str_pad($num, 2, '0', STR_PAD_LEFT);
        }

        // 2. Suggest code for Hama
        $lastHama = Hama::orderBy('kode_hama', 'desc')->first();
        $suggestedHama = 'H01';
        if ($lastHama && preg_match('/H(\d+)/', $lastHama->kode_hama, $matches)) {
            $num = (int)$matches[1] + 1;
            $suggestedHama = 'H' . str_pad($num, 2, '0', STR_PAD_LEFT);
        }

        // 3. Suggest code for Gejala
        $lastGejala = Gejala::orderBy('kode_gejala', 'desc')->first();
        $suggestedGejala = 'G01';
        if ($lastGejala && preg_match('/G(\d+)/', $lastGejala->kode_gejala, $matches)) {
            $num = (int)$matches[1] + 1;
            $suggestedGejala = 'G' . str_pad($num, 2, '0', STR_PAD_LEFT);
        }

        return view('pakar.master.create', compact('suggestedPenyakit', 'suggestedHama', 'suggestedGejala', 'defaultType'));
    }

    public function storeMaster(Request $request)
    {
        $dataType = $request->input('dataType');

        if ($dataType === 'penyakit') {
            $request->validate([
                'kode' => 'required|string|unique:penyakit,kode_penyakit',
                'nama' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            $penyakit = Penyakit::create([
                'kode_penyakit' => strtoupper($request->kode),
                'nama_penyakit' => $request->nama,
                'slug' => Str::slug($request->nama),
                'deskripsi' => $request->deskripsi,
                'created_by' => Auth::id()
            ]);

            // Auto-create Library entry so it appears in the encyclopedia
            $exists = Library::where('jenis', 'penyakit')->where('nama', $request->nama)->exists();
            if (!$exists) {
                Library::create([
                    'jenis' => 'penyakit',
                    'nama' => $request->nama,
                    'deskripsi' => $request->deskripsi ?? 'Deskripsi penyakit ' . $request->nama . '.',
                    'penyebab' => 'Penyebab belum ditentukan.',
                    'solusi' => 'Hubungi pakar pertanian setempat untuk konsultasi.',
                    'pencegahan' => 'Lakukan sanitasi dan monitoring berkala.',
                    'created_by' => Auth::id()
                ]);
            }

            return redirect()->route('pakar.dashboard')->with('success', 'Penyakit Baru berhasil ditambahkan dan disinkronisasi ke Ensiklopedia.');

        } elseif ($dataType === 'hama') {
            $request->validate([
                'kode' => 'required|string|unique:hama,kode_hama',
                'nama' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            $hama = Hama::create([
                'kode_hama' => strtoupper($request->kode),
                'nama_hama' => $request->nama,
                'slug' => Str::slug($request->nama),
                'deskripsi' => $request->deskripsi,
                'created_by' => Auth::id()
            ]);

            // Auto-create Library entry so it appears in the encyclopedia
            $exists = Library::where('jenis', 'hama')->where('nama', $request->nama)->exists();
            if (!$exists) {
                Library::create([
                    'jenis' => 'hama',
                    'nama' => $request->nama,
                    'deskripsi' => $request->deskripsi ?? 'Deskripsi hama ' . $request->nama . '.',
                    'penyebab' => 'Penyebab belum ditentukan.',
                    'solusi' => 'Hubungi pakar pertanian setempat untuk konsultasi.',
                    'pencegahan' => 'Lakukan sanitasi dan monitoring berkala.',
                    'created_by' => Auth::id()
                ]);
            }

            return redirect()->route('pakar.dashboard')->with('success', 'Hama Baru berhasil ditambahkan dan disinkronisasi ke Ensiklopedia.');

        } elseif ($dataType === 'gejala') {
            $request->validate([
                'kode' => 'required|string|unique:gejala,kode_gejala',
                'nama' => 'required|string|max:255',
                'kategori' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            Gejala::create([
                'kode_gejala' => strtoupper($request->kode),
                'nama_gejala' => $request->nama,
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'created_by' => Auth::id()
            ]);

            return redirect()->route('pakar.dashboard')->with('success', 'Gejala Baru berhasil ditambahkan.');
        }

        return redirect()->route('pakar.dashboard')->with('error', 'Tipe data tidak valid.');
    }
}

