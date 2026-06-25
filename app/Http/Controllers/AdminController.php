<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rule;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Hama;
use App\Models\Library;
use App\Models\DiagnosisHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $usersCount = User::count();
        $rulesCount = Rule::count();
        $libraryCount = Library::count();
        $historyCount = DiagnosisHistory::count();

        return view('admin.dashboard', compact('usersCount', 'rulesCount', 'libraryCount', 'historyCount'));
    }

    // ================== USER MANAGEMENT ==================
    public function searchGejala(Request $request)
    {
        return Gejala::where('nama_gejala','like','%'.$request->q.'%')
            ->limit(5)
            ->get();
    }

    public function searchTarget(Request $request)
{
    if ($request->type == 'penyakit') {
        return Penyakit::where('nama_penyakit', 'like', '%' . $request->q . '%')
            ->limit(5)
            ->get();
    }

    return Hama::where('nama_hama', 'like', '%' . $request->q . '%')
        ->limit(5)
        ->get();
}

public function searchPenyakit(Request $request)
{
    return Penyakit::where('nama_penyakit','like','%'.$request->q.'%')
        ->limit(5)
        ->get();
}

public function searchHama(Request $request)
{
    return Hama::where('nama_hama','like','%'.$request->q.'%')
        ->limit(5)
        ->get();
}

    public function indexUsers()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,pakar,admin',
            'status' => 'required|in:active,inactive'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:user,pakar,admin',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:6'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deactivating their own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri.');
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $message = $newStatus === 'active'
            ? "Akun {$user->name} berhasil diaktifkan."
            : "Akun {$user->name} berhasil dinonaktifkan.";

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    // ================== RULES MANAGEMENT ==================
    public function indexRules()
    {
        $rules = Rule::with(['gejala','target'])->get();
        $gejala = Gejala::all();
        $penyakit = Penyakit::all();
        $hama = Hama::all();

        return view('admin.rules.index', compact(
            'rules',
            'gejala',
            'penyakit',
            'hama'
        ));
    }

    public function createRule()
    {
        $gejala = Gejala::orderBy('kode_gejala')->get();
        $penyakit = Penyakit::orderBy('nama_penyakit')->get();
        $hama = Hama::orderBy('nama_hama')->get();

        return view('admin.rules.create', compact('gejala', 'penyakit', 'hama'));
    }

    public function storeRule(Request $request)
    {
        $request->validate([
            'target_type' => 'required|in:penyakit,hama',
            'cf_pakar' => 'required|numeric|between:0,1',
            'gejala_id' => 'nullable|integer|exists:gejala,id',
            'nama_gejala' => 'nullable|string|max:255',
            'target_id' => 'nullable|integer',
            'nama_penyakit' => 'nullable|string|max:255',
            'nama_hama' => 'nullable|string|max:255',
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
                        'created_by' => auth()->id(),
                    ]
                );
                $gejalaId = $gejala->id;
            }
        }

        if (!$gejalaId) {
            return back()->withErrors(['nama_gejala' => 'Silakan pilih atau isi gejala baru.'])->withInput();
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
                            'created_by' => auth()->id(),
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
                            'created_by' => auth()->id(),
                        ]
                    );
                    $targetId = $target->id;
                    $this->syncLibrary($targetType, $target->nama_hama);
                }
            }
        }

        if (!$targetId) {
            return back()->withErrors(['target_id' => 'Silakan pilih atau isi penyakit/hama baru.'])->withInput();
        }

        Rule::firstOrCreate(
            [
                'gejala_id' => $gejalaId,
                'target_type' => $targetType,
                'target_id' => $targetId,
            ],
            [
                'cf_pakar' => $request->cf_pakar,
                'created_by' => auth()->id(),
            ]
        );

        return redirect()->route('admin.rules.index')->with('success', 'Rule CF berhasil ditambahkan.');
    }

    public function editRule($id)
    {
        $rule = Rule::findOrFail($id);
        $gejala = Gejala::orderBy('kode_gejala')->get();
        $penyakit = Penyakit::orderBy('nama_penyakit')->get();
        $hama = Hama::orderBy('nama_hama')->get();

        return view('admin.rules.edit', compact('rule', 'gejala', 'penyakit', 'hama'));
    }

    public function updateRule(Request $request, $id)
    {
        $rule = Rule::findOrFail($id);

        $request->validate([
            'gejala_id' => 'required|exists:gejala,id',
            'target_type' => 'required|in:penyakit,hama',
            'target_id' => 'required|integer',
            'cf_pakar' => 'required|numeric|between:0,1'
        ]);

        $rule->update($request->all());

        return redirect()->route('admin.rules.index')->with('success', 'Rule CF berhasil diperbarui.');
    }

    public function deleteRule($id)
    {
        $rule = Rule::findOrFail($id);
        $rule->delete();
        return redirect()->route('admin.rules.index')->with('success', 'Rule CF berhasil dihapus.');
    }

    // ================== LIBRARY MANAGEMENT ==================
    public function indexLibrary()
    {
        $libraries = Library::orderBy('nama')->get();
        return view('admin.library.index', compact('libraries'));
    }

    public function createLibrary()
    {
        return view('admin.library.create');
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
            'gambar' => 'nullable|url'
        ]);

        Library::create(array_merge($request->all(), ['created_by' => auth()->id()]));

        return redirect()->route('admin.library.index')->with('success', 'Library berhasil ditambahkan.');
    }

    public function editLibrary($id)
    {
        $library = Library::findOrFail($id);
        return view('admin.library.edit', compact('library'));
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
            'gambar' => 'nullable|url'
        ]);

        $library->update($request->all());

        return redirect()->route('admin.library.index')->with('success', 'Library berhasil diperbarui.');
    }

    public function deleteLibrary($id)
    {
        $library = Library::findOrFail($id);
        $library->delete();
        return redirect()->route('admin.library.index')->with('success', 'Library berhasil dihapus.');
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
                'created_by' => auth()->id(),
            ]
        );
    }
}
