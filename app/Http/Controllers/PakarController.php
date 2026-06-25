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
    public function searchGejala(Request $request)
    {
        return Gejala::where('nama_gejala', 'like', '%' . $request->q . '%')
            ->limit(5)
            ->get();
    }

    public function searchPenyakit(Request $request)
    {
        return Penyakit::where('nama_penyakit', 'like', '%' . $request->q . '%')
            ->limit(5)
            ->get();
    }

    public function searchHama(Request $request)
    {
        return Hama::where('nama_hama', 'like', '%' . $request->q . '%')
            ->limit(5)
            ->get();
    }

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
        if (!$request->has('symptom_rows')) {
            $request->validate([
                'target_type' => 'required|in:penyakit,hama',
                'cf_pakar' => 'required|numeric|between:0,1',
            ]);

            $gejalaId = $request->input('gejala_id');
            if (!$gejalaId && $request->filled('nama_gejala')) {
                $namaGejala = trim($request->nama_gejala);
                if ($namaGejala !== '') {
                    $gejala = Gejala::firstOrCreate(
                        ['nama_gejala' => $namaGejala],
                        [
                            'kode_gejala' => $this->nextCode(Gejala::class, 'kode_gejala', 'G'),
                            'kategori' => 'Seluruh Tanaman',
                            'created_by' => Auth::id(),
                        ]
                    );
                    $gejalaId = $gejala->id;
                }
            }

            if (!$gejalaId) {
                return back()->withErrors(['nama_gejala' => 'Silakan pilih atau isi gejala.'])->withInput();
            }

            $targetType = $request->input('target_type');
            $targetId = $request->input('target_id');

            if (!$targetId) {
                if ($targetType === 'penyakit' && $request->filled('nama_penyakit')) {
                    $namaTarget = trim($request->nama_penyakit);
                    if ($namaTarget !== '') {
                        $target = Penyakit::firstOrCreate(
                            ['nama_penyakit' => $namaTarget],
                            [
                                'kode_penyakit' => $this->nextCode(Penyakit::class, 'kode_penyakit', 'P'),
                                'slug' => Str::slug($namaTarget),
                                'created_by' => Auth::id(),
                            ]
                        );
                        $targetId = $target->id;
                        $this->syncLibrary($targetType, $target->nama_penyakit);
                    }
                } elseif ($targetType === 'hama' && $request->filled('nama_hama')) {
                    $namaTarget = trim($request->nama_hama);
                    if ($namaTarget !== '') {
                        $target = Hama::firstOrCreate(
                            ['nama_hama' => $namaTarget],
                            [
                                'kode_hama' => $this->nextCode(Hama::class, 'kode_hama', 'H'),
                                'slug' => Str::slug($namaTarget),
                                'created_by' => Auth::id(),
                            ]
                        );
                        $targetId = $target->id;
                        $this->syncLibrary($targetType, $target->nama_hama);
                    }
                }
            }

            if (!$targetId) {
                return back()->withErrors(['target_id' => 'Silakan pilih atau isi target penyakit/hama.'])->withInput();
            }

            Rule::firstOrCreate(
                [
                    'gejala_id' => $gejalaId,
                    'target_type' => $targetType,
                    'target_id' => $targetId,
                ],
                [
                    'cf_pakar' => $request->cf_pakar,
                    'created_by' => Auth::id(),
                ]
            );

            return redirect()->route('pakar.rules.index')->with('success', 'Rule CF berhasil ditambahkan.');
        }

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

    public function editRule($id)
    {
        $rule = Rule::findOrFail($id);
        $gejala = Gejala::orderBy('kode_gejala')->get();
        $penyakit = Penyakit::orderBy('nama_penyakit')->get();
        $hama = Hama::orderBy('nama_hama')->get();

        return view('pakar.rules.edit', compact('rule', 'gejala', 'penyakit', 'hama'));
    }

    public function updateRule(Request $request, $id)
    {
        $rule = Rule::findOrFail($id);

        $request->validate([
            'gejala_id' => 'required|exists:gejala,id',
            'target_type' => 'required|in:penyakit,hama',
            'target_id' => 'required|integer',
            'cf_pakar' => 'required|numeric|between:0,1',
        ]);

        $rule->update($request->only('gejala_id', 'target_type', 'target_id', 'cf_pakar'));

        return redirect()->route('pakar.rules.index')->with('success', 'Rule CF berhasil diperbarui.');
    }

    public function deleteRule($id)
    {
        Rule::findOrFail($id)->delete();

        return redirect()->route('pakar.rules.index')->with('success', 'Rule CF berhasil dihapus.');
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

    public function editLibrary($id)
    {
        $library = Library::findOrFail($id);

        return view('pakar.library.edit', compact('library'));
    }

    public function updateLibrary(Request $request, $id)
    {
        $library = Library::findOrFail($id);

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

        $library->update($request->only(
            'jenis',
            'nama',
            'nama_latin',
            'deskripsi',
            'penyebab',
            'solusi',
            'pencegahan',
            'gambar'
        ));

        return redirect()->route('pakar.library.index')->with('success', 'Artikel library berhasil diperbarui.');
    }

    public function deleteLibrary($id)
    {
        Library::findOrFail($id)->delete();

        return redirect()->route('pakar.library.index')->with('success', 'Artikel library berhasil dihapus.');
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
            'nama_gejala' => 'required|string|max:255',
            'kode_gejala' => 'nullable|string|max:20',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $namaGejala = trim($request->nama_gejala);
        if ($namaGejala === '') {
            return back()->withErrors(['nama_gejala' => 'Nama gejala harus diisi.'])->withInput();
        }

        $gejala = Gejala::where('nama_gejala', $namaGejala)->first();
        if (!$gejala) {
            $kode = strtoupper(trim($request->kode_gejala ?? ''));
            if ($kode === '' || Gejala::where('kode_gejala', $kode)->exists()) {
                $kode = $this->nextCode(Gejala::class, 'kode_gejala', 'G');
            }

            Gejala::create([
                'kode_gejala' => $kode,
                'nama_gejala' => $namaGejala,
                'kategori' => $request->kategori ?: 'Seluruh Tanaman',
                'deskripsi' => $request->deskripsi,
                'created_by' => Auth::id()
            ]);
        }

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
            'nama_penyakit' => 'required|string|max:255',
            'kode_penyakit' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
        ]);

        $namaPenyakit = trim($request->nama_penyakit);
        if ($namaPenyakit === '') {
            return back()->withErrors(['nama_penyakit' => 'Nama penyakit harus diisi.'])->withInput();
        }

        $penyakit = Penyakit::where('nama_penyakit', $namaPenyakit)->first();
        if (!$penyakit) {
            $kode = strtoupper(trim($request->kode_penyakit ?? ''));
            if ($kode === '' || Penyakit::where('kode_penyakit', $kode)->exists()) {
                $kode = $this->nextCode(Penyakit::class, 'kode_penyakit', 'P');
            }

            $penyakit = Penyakit::create([
                'kode_penyakit' => $kode,
                'nama_penyakit' => $namaPenyakit,
                'slug' => Str::slug($namaPenyakit),
                'deskripsi' => $request->deskripsi,
                'created_by' => Auth::id()
            ]);
        }

        $this->syncLibrary('penyakit', $penyakit->nama_penyakit);

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
            'nama_hama' => 'required|string|max:255',
            'kode_hama' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
        ]);

        $namaHama = trim($request->nama_hama);
        if ($namaHama === '') {
            return back()->withErrors(['nama_hama' => 'Nama hama harus diisi.'])->withInput();
        }

        $hama = Hama::where('nama_hama', $namaHama)->first();
        if (!$hama) {
            $kode = strtoupper(trim($request->kode_hama ?? ''));
            if ($kode === '' || Hama::where('kode_hama', $kode)->exists()) {
                $kode = $this->nextCode(Hama::class, 'kode_hama', 'H');
            }

            $hama = Hama::create([
                'kode_hama' => $kode,
                'nama_hama' => $namaHama,
                'slug' => Str::slug($namaHama),
                'deskripsi' => $request->deskripsi,
                'created_by' => Auth::id()
            ]);
        }

        $this->syncLibrary('hama', $hama->nama_hama);

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
                'nama' => 'required|string|max:255',
                'kode' => 'nullable|string|max:20',
                'deskripsi' => 'nullable|string',
            ]);

            $nama = trim($request->nama);
            if ($nama === '') {
                return back()->withErrors(['nama' => 'Nama penyakit harus diisi.'])->withInput();
            }

            $penyakit = Penyakit::where('nama_penyakit', $nama)->first();
            if (!$penyakit) {
                $kode = strtoupper(trim($request->kode ?? ''));
                if ($kode === '' || Penyakit::where('kode_penyakit', $kode)->exists()) {
                    $kode = $this->nextCode(Penyakit::class, 'kode_penyakit', 'P');
                }

                $penyakit = Penyakit::create([
                    'kode_penyakit' => $kode,
                    'nama_penyakit' => $nama,
                    'slug' => Str::slug($nama),
                    'deskripsi' => $request->deskripsi,
                    'created_by' => Auth::id()
                ]);
            }

            $this->syncLibrary('penyakit', $penyakit->nama_penyakit);

            return redirect()->route('pakar.dashboard')->with('success', 'Penyakit Baru berhasil ditambahkan dan disinkronisasi ke Ensiklopedia.');

        } elseif ($dataType === 'hama') {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'nullable|string|max:20',
                'deskripsi' => 'nullable|string',
            ]);

            $nama = trim($request->nama);
            if ($nama === '') {
                return back()->withErrors(['nama' => 'Nama hama harus diisi.'])->withInput();
            }

            $hama = Hama::where('nama_hama', $nama)->first();
            if (!$hama) {
                $kode = strtoupper(trim($request->kode ?? ''));
                if ($kode === '' || Hama::where('kode_hama', $kode)->exists()) {
                    $kode = $this->nextCode(Hama::class, 'kode_hama', 'H');
                }

                $hama = Hama::create([
                    'kode_hama' => $kode,
                    'nama_hama' => $nama,
                    'slug' => Str::slug($nama),
                    'deskripsi' => $request->deskripsi,
                    'created_by' => Auth::id()
                ]);
            }

            $this->syncLibrary('hama', $hama->nama_hama);

            return redirect()->route('pakar.dashboard')->with('success', 'Hama Baru berhasil ditambahkan dan disinkronisasi ke Ensiklopedia.');

        } elseif ($dataType === 'gejala') {
            $request->validate([
                'nama' => 'required|string|max:255',
                'kode' => 'nullable|string|max:20',
                'kategori' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            $nama = trim($request->nama);
            if ($nama === '') {
                return back()->withErrors(['nama' => 'Nama gejala harus diisi.'])->withInput();
            }

            $gejala = Gejala::where('nama_gejala', $nama)->first();
            if (!$gejala) {
                $kode = strtoupper(trim($request->kode ?? ''));
                if ($kode === '' || Gejala::where('kode_gejala', $kode)->exists()) {
                    $kode = $this->nextCode(Gejala::class, 'kode_gejala', 'G');
                }

                Gejala::create([
                    'kode_gejala' => $kode,
                    'nama_gejala' => $nama,
                    'kategori' => $request->kategori ?: 'Seluruh Tanaman',
                    'deskripsi' => $request->deskripsi,
                    'created_by' => Auth::id()
                ]);
            }

            return redirect()->route('pakar.dashboard')->with('success', 'Gejala Baru berhasil ditambahkan.');
        }

        return redirect()->route('pakar.dashboard')->with('error', 'Tipe data tidak valid.');
    }

    private function nextCode(string $modelClass, string $column, string $prefix): string
    {
        $last = $modelClass::orderBy($column, 'desc')->first();
        $next = 1;

        if ($last && preg_match('/' . preg_quote($prefix, '/') . '(\d+)/', $last->{$column}, $matches)) {
            $next = (int) $matches[1] + 1;
        }

        return $prefix . str_pad($next, 2, '0', STR_PAD_LEFT);
    }

    private function syncLibrary(string $jenis, string $nama): void
    {
        Library::firstOrCreate(
            [
                'jenis' => $jenis,
                'nama' => $nama,
            ],
            [
                'deskripsi' => 'Deskripsi penyakit/hama padi ' . $nama . '.',
                'penyebab' => 'Penyebab belum ditentukan.',
                'solusi' => 'Hubungi pakar pertanian setempat untuk konsultasi.',
                'pencegahan' => 'Lakukan monitoring tanaman padi secara berkala.',
                'created_by' => Auth::id(),
            ]
        );
    }
}
