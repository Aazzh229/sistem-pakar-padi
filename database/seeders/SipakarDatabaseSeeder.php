<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Hama;
use App\Models\Rule;
use App\Models\Library;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SipakarDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        $pakar = User::create([
            'name' => 'Dr. Budi',
            'email' => 'pakar@example.com',
            'password' => Hash::make('password'),
            'role' => 'pakar',
            'status' => 'active',
            'created_by' => $admin->id
        ]);

        User::create([
            'name' => 'Pak Tani',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'created_by' => $pakar->id
        ]);

        // 2. Seed Gejala
        $gejalaData = [
            ['G01', 'Bercak belah ketupat di daun', 'Daun', 'Bercak berbentuk belah ketupat lebar di bagian tengah dan meruncing di kedua ujung daun.'],
            ['G02', 'Tepian kuning', 'Daun', 'Tepian daun atau bercak berwarna kuning keemasan.'],
            ['G03', 'Tengah abu-abu/coklat keputihan', 'Daun', 'Bagian tengah bercak berwarna abu-abu atau coklat keputihan.'],
            ['G04', 'Pangkal leher malai membusuk/coklat', 'Malai', 'Pangkal leher malai berubah warna menjadi coklat kehitaman dan membusuk.'],
            ['G05', 'Malai patah atau terkulai', 'Malai', 'Malai patah pada lehernya atau terkulai lemas.'],
            ['G06', 'Bulir hampa', 'Malai', 'Bulir padi tidak terisi beras (kosong/hampa).'],
            ['G07', 'Batang terpotong', 'Batang', 'Batang padi terpotong rapi di bagian pangkal.'],
            ['G08', 'Bekas gigitan di pangkal batang', 'Batang', 'Terdapat bekas keratan atau gigitan hewan pengerat pada pangkal batang.'],
            ['G09', 'Kerusakan menyebar dalam petak', 'Tanaman', 'Kerusakan tanaman terlokalisir menyebar melingkar dalam petakan sawah.'],
            ['G10', 'Serangga kecil coklat di pangkal batang', 'Batang', 'Serangga kecil bersayap/tidak berwarna coklat berkumpul di pangkal batang padi.'],
            ['G11', 'Tanaman tampak terbakar', 'Tanaman', 'Rumpun tanaman menguning cepat, kering, dan tampak seperti terbakar (hopperburn).'],
            ['G12', 'Tanaman kerdil', 'Tanaman', 'Pertumbuhan tinggi tanaman jauh lebih pendek dari ukuran normal.'],
            ['G13', 'Daun jingga/kuning dari ujung ke bawah', 'Daun', 'Daun berubah warna menjadi kuning-jingga mulai dari ujung daun merambat ke bawah.'],
            ['G14', 'Pucuk batang layu/kering', 'Batang', 'Pucuk batang padi bagian tengah layu, mengering, dan mati (sundep).'],
            ['G15', 'Pucuk mudah dicabut', 'Batang', 'Pucuk batang yang layu sangat mudah dicabut saat ditarik.'],
            ['G16', 'Fase vegetatif', 'Tanaman', 'Gejala muncul pada tahap pertumbuhan awal sebelum berbunga.'],
            ['G17', 'Malai putih/kering', 'Malai', 'Malai keluar berwarna putih keperakan, tegak, dan seluruh bulirnya hampa (beluk).'],
            ['G18', 'Fase generatif', 'Tanaman', 'Gejala muncul pada tahap padi mulai berbunga atau berbulir.'],
            ['G19', 'Tanaman muda dimakan', 'Tanaman', 'Batang dan daun padi yang baru ditanam habis termakan siput.'],
            ['G20', 'Telur merah jambu', 'Tanaman', 'Kelompok telur berwarna merah jambu cerah menempel pada batang padi atau pematang.'],
            ['G21', 'Sawah tergenang', 'Tanaman', 'Kondisi sawah memiliki genangan air yang cukup tinggi.'],
            ['G22', 'Gejala mulai dari tepi daun', 'Daun', 'Bercak basah keabu-abuan muncul mulai dari tepi atau pucuk daun.'],
            ['G23', 'Menyebar ke ujung/pangkal', 'Daun', 'Hawar daun melebar merambat sepanjang tepi daun menuju pangkal daun.'],
            ['G24', 'Daun hawar/kering', 'Daun', 'Daun layu, mengering keriput, berwarna abu-abu keputihan.'],
            ['G25', 'Daun menggulung/melipat', 'Daun', 'Daun padi melipat sejajar dengan tulang daun utama.'],
            ['G26', 'Terdapat ulat/larva kecil', 'Daun', 'Terdapat ulat kecil hijau di dalam lipatan daun yang memakan daging daun.'],
            ['G27', 'Serangga berbau menyengat pada malai', 'Malai', 'Serangga hijau kecoklatan berkaki panjang hinggap di malai dan mengeluarkan bau busuk.'],
            ['G28', 'Bercak coklat bulat/oval', 'Daun', 'Bercak coklat berbentuk bulat atau oval tersebar di permukaan daun.'],
            ['G29', 'Kekurangan unsur hara N atau K', 'Tanaman', 'Kondisi tanah sawah kekurangan pemupukan Urea atau KCl.'],
            ['G30', 'Tunas berbentuk pentil/daun bawang', 'Batang', 'Tunas anakan padi tumbuh aneh menyerupai pipa daun bawang.'],
            ['G31', 'Populasi wereng sangat tinggi', 'Tanaman', 'Kepadatan serangga wereng sangat tinggi di pangkal tanaman.'],
            ['G32', 'Terdapat eksudat putih kekuningan', 'Daun', 'Terdapat cairan/butiran embun bakteri berwarna putih kekuningan pada luka daun.'],
            ['G33', 'Tanaman tumbuh normal', 'Tanaman', 'Tanaman menunjukkan pertumbuhan yang sehat dan normal.']
        ];

        $gejalaModels = [];
        foreach ($gejalaData as $gd) {
            $gejalaModels[$gd[0]] = Gejala::create([
                'kode_gejala' => $gd[0],
                'nama_gejala' => $gd[1],
                'kategori' => $gd[2],
                'deskripsi' => $gd[3]
            ]);
        }

        // 3. Seed Penyakit
        $penyakitData = [
            ['P01', 'Blast Padi'],
            ['P03', 'Tungro'],
            ['P04', 'Hawar Daun Bakteri'],
            ['P05', 'Bercak Coklat'],
            ['P07', 'Busuk Pelepah']
        ];

        $penyakitModels = [];
        foreach ($penyakitData as $pd) {
            $penyakitModels[$pd[0]] = Penyakit::create([
                'kode_penyakit' => $pd[0],
                'nama_penyakit' => $pd[1],
                'slug' => Str::slug($pd[1])
            ]);
        }

        // 4. Seed Hama
        $hamaData = [
            ['H01', 'Tikus Sawah'],
            ['H02', 'Wereng Coklat'],
            ['H03', 'Penggerek Batang'],
            ['H05', 'Keong Mas'],
            ['H07', 'Walang Sangit']
        ];

        $hamaModels = [];
        foreach ($hamaData as $hd) {
            $hamaModels[$hd[0]] = Hama::create([
                'kode_hama' => $hd[0],
                'nama_hama' => $hd[1],
                'slug' => Str::slug($hd[1])
            ]);
        }

        // 5. Seed Rules (CF Pakar Weights)
        // Blast Padi Rules (P01)
        Rule::create(['gejala_id' => $gejalaModels['G01']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P01']->id, 'cf_pakar' => 0.90, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G02']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P01']->id, 'cf_pakar' => 0.85, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G03']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P01']->id, 'cf_pakar' => 0.80, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G04']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P01']->id, 'cf_pakar' => 0.90, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G05']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P01']->id, 'cf_pakar' => 0.85, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G06']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P01']->id, 'cf_pakar' => 0.80, 'created_by' => $pakar->id]);

        // Tungro Rules (P03)
        Rule::create(['gejala_id' => $gejalaModels['G12']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P03']->id, 'cf_pakar' => 0.85, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G13']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P03']->id, 'cf_pakar' => 0.80, 'created_by' => $pakar->id]);

        // Hawar Daun Bakteri (P04)
        Rule::create(['gejala_id' => $gejalaModels['G22']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P04']->id, 'cf_pakar' => 0.85, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G23']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P04']->id, 'cf_pakar' => 0.80, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G24']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P04']->id, 'cf_pakar' => 0.80, 'created_by' => $pakar->id]);

        // Bercak Coklat Rules (P05)
        Rule::create(['gejala_id' => $gejalaModels['G28']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P05']->id, 'cf_pakar' => 0.80, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G29']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P05']->id, 'cf_pakar' => 0.75, 'created_by' => $pakar->id]);

        // Busuk Pelepah Rules (P07)
        Rule::create(['gejala_id' => $gejalaModels['G02']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P07']->id, 'cf_pakar' => 0.75, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G28']->id, 'target_type' => 'penyakit', 'target_id' => $penyakitModels['P07']->id, 'cf_pakar' => 0.70, 'created_by' => $pakar->id]);

        // Tikus Sawah Rules (H01)
        Rule::create(['gejala_id' => $gejalaModels['G07']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H01']->id, 'cf_pakar' => 0.80, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G08']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H01']->id, 'cf_pakar' => 0.80, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G09']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H01']->id, 'cf_pakar' => 0.75, 'created_by' => $pakar->id]);

        // Wereng Coklat Rules (H02)
        Rule::create(['gejala_id' => $gejalaModels['G10']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H02']->id, 'cf_pakar' => 0.95, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G11']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H02']->id, 'cf_pakar' => 0.90, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G31']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H02']->id, 'cf_pakar' => 0.85, 'created_by' => $pakar->id]);

        // Penggerek Batang Rules (H03)
        Rule::create(['gejala_id' => $gejalaModels['G14']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H03']->id, 'cf_pakar' => 0.85, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G15']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H03']->id, 'cf_pakar' => 0.80, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G17']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H03']->id, 'cf_pakar' => 0.80, 'created_by' => $pakar->id]);

        // Keong Mas Rules (H05)
        Rule::create(['gejala_id' => $gejalaModels['G19']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H05']->id, 'cf_pakar' => 0.85, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G20']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H05']->id, 'cf_pakar' => 0.90, 'created_by' => $pakar->id]);

        // Walang Sangit Rules (H07)
        Rule::create(['gejala_id' => $gejalaModels['G27']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H07']->id, 'cf_pakar' => 0.85, 'created_by' => $pakar->id]);
        Rule::create(['gejala_id' => $gejalaModels['G06']->id, 'target_type' => 'hama', 'target_id' => $hamaModels['H07']->id, 'cf_pakar' => 0.75, 'created_by' => $pakar->id]);

        // 6. Seed Library
        $libraryData = [
            [
                'jenis' => 'penyakit',
                'nama' => 'Blast Padi',
                'nama_latin' => 'Pyricularia oryzae',
                'deskripsi' => 'Penyakit jamur yang menyerang daun, batang, dan malai padi.',
                'penyebab' => 'Jamur Pyricularia oryzae',
                'solusi' => 'Gunakan varietas tahan penyakit, kurangi pemakaian pupuk nitrogen berlebih, dan semprot fungisida berbahan aktif trisiklazol bila intensitas serangan tinggi.',
                'pencegahan' => 'Pengaturan jarak tanam legowo dan pembersihan sisa-sisa jerami padi yang terinfeksi.',
                'gambar' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=400&auto=format&fit=crop&q=80'
            ],
            [
                'jenis' => 'penyakit',
                'nama' => 'Tungro',
                'nama_latin' => 'Rice tungro bacilliform virus',
                'deskripsi' => 'Penyakit virus yang ditularkan oleh wereng hijau, menyebabkan tanaman padi kerdil dan daun berwarna kuning jingga.',
                'penyebab' => 'Virus Tungro (RTBV dan RTSV)',
                'solusi' => 'Kendalikan vektor wereng hijau dengan insektisida sistemik dan musnahkan tanaman yang terinfeksi berat.',
                'pencegahan' => 'Tanam serempak untuk memutus siklus hidup wereng hijau.',
                'gambar' => 'https://images.unsplash.com/photo-1628352081506-83c43123ed6d?w=400&auto=format&fit=crop&q=80'
            ],
            [
                'jenis' => 'penyakit',
                'nama' => 'Hawar Daun Bakteri',
                'nama_latin' => 'Xanthomonas oryzae pv. oryzae',
                'deskripsi' => 'Penyakit bakteri yang menyerang daun padi, ditandai dengan garis basah pada helai daun yang mengering seperti terbakar.',
                'penyebab' => 'Bakteri Xanthomonas oryzae pv. oryzae',
                'solusi' => 'Kurangi pengairan berlebih, hindari pemupukan N yang berlebih, dan aplikasikan bakterisida.',
                'pencegahan' => 'Gunakan varietas tahan seperti Inpari, dan gunakan sistem pengairan berselang (intermittent).',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ],
            [
                'jenis' => 'penyakit',
                'nama' => 'Bercak Coklat',
                'nama_latin' => 'Helminthosporium oryzae',
                'deskripsi' => 'Infeksi jamur yang menyebabkan bercak oval berwarna coklat pada permukaan daun padi.',
                'penyebab' => 'Jamur Helminthosporium oryzae',
                'solusi' => 'Berikan pupuk kalium (K) yang cukup, lakukan pemupukan berimbang, dan gunakan fungisida jika diperlukan.',
                'pencegahan' => 'Perbaiki drainase tanah dan gunakan benih bebas patogen.',
                'gambar' => 'https://images.unsplash.com/photo-1628352081506-83c43123ed6d?w=400&auto=format&fit=crop&q=80'
            ],
            [
                'jenis' => 'penyakit',
                'nama' => 'Busuk Pelepah',
                'nama_latin' => 'Rhizoctonia solani',
                'deskripsi' => 'Penyakit jamur yang merusak pelepah daun padi, ditandai dengan bercak bulat tidak beraturan berwarna abu-abu kehijauan.',
                'penyebab' => 'Jamur Rhizoctonia solani',
                'solusi' => 'Kurangi pemakaian pupuk N berlebih, perbaiki drainase sawah, dan semprot fungisida berbahan aktif dipenokonazol jika parah.',
                'pencegahan' => 'Lakukan sanitasi gulma di sekitar sawah dan atur jarak tanam.',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ],
            [
                'jenis' => 'hama',
                'nama' => 'Tikus Sawah',
                'nama_latin' => 'Rattus argentiventer',
                'deskripsi' => 'Hama pengerat yang merusak tanaman padi pada semua fase pertumbuhan dengan memotong batang padi.',
                'penyebab' => 'Tikus Sawah (Rattus argentiventer)',
                'solusi' => 'Lakukan gropyokan massal, pasang TBS (Trap Barrier System), atau gunakan rodentisida.',
                'pencegahan' => 'Pembersihan semak-semak dan tanggul sawah agar tidak menjadi sarang tikus.',
                'gambar' => 'https://images.unsplash.com/photo-1428908728789-d2de25dbd4e2?w=400&auto=format&fit=crop&q=80'
            ],
            [
                'jenis' => 'hama',
                'nama' => 'Wereng Coklat',
                'nama_latin' => 'Nilaparvata lugens',
                'deskripsi' => 'Hama penghisap cairan batang padi yang paling merusak, menyebabkan tanaman mengering kecoklatan (hopperburn).',
                'penyebab' => 'Wereng Batang Coklat (Nilaparvata lugens)',
                'solusi' => 'Gunakan musuh alami seperti laba-laba, semprot insektisida berbahan aktif pymetrozine jika populasi ambang batas terlampaui.',
                'pencegahan' => 'Hindari penggunaan insektisida spektrum luas di awal musim tanam.',
                'gambar' => 'https://images.unsplash.com/photo-1508849789987-4e5333c12b78?w=400&auto=format&fit=crop&q=80'
            ],
            [
                'jenis' => 'hama',
                'nama' => 'Penggerek Batang',
                'nama_latin' => 'Scirpophaga innotata',
                'deskripsi' => 'Larva ngengat penggerek batang padi yang menyerang pada fase vegetatif menyebabkan anakan mati (sundep).',
                'penyebab' => 'Ulat Penggerek Batang Padi (Scirpophaga innotata / incertulas)',
                'solusi' => 'Kumpulkan telur penggerek di lapangan, pasang perangkap lampu (light trap), semprot insektisida sistemik.',
                'pencegahan' => 'Tanam serentak dan lakukan pembajakan tanah sawah setelah panen untuk membunuh larva di jerami.',
                'gambar' => 'placeholder'
            ],
            [
                'jenis' => 'hama',
                'nama' => 'Keong Mas',
                'nama_latin' => 'Pomacea canaliculata',
                'deskripsi' => 'Siput air tawar yang memakan tunas-tunas padi muda yang baru ditanam pada sawah yang tergenang.',
                'penyebab' => 'Keong Mas (Pomacea canaliculata)',
                'solusi' => 'Kumpulkan keong secara manual, buat parit kecil di keliling petakan sawah, atau taburkan moluskisida.',
                'pencegahan' => 'Pasang saringan pada pintu air masuk sawah agar bayi keong tidak masuk.',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ],
            [
                'jenis' => 'hama',
                'nama' => 'Walang Sangit',
                'nama_latin' => 'Leptocorisa oratorius',
                'deskripsi' => 'Serangga penghisap bulir padi muda (fase masak susu) yang mengeluarkan bau busuk menyengat.',
                'penyebab' => 'Walang Sangit (Leptocorisa oratorius)',
                'solusi' => 'Semprot insektisida kontak di pagi hari ketika walang sangit berkumpul di bagian atas tanaman.',
                'pencegahan' => 'Bersihkan rumput-rumput liar di pematang sawah yang menjadi inang alternatif.',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ]
        ];

        foreach ($libraryData as $ld) {
            Library::create(array_merge($ld, ['created_by' => $pakar->id]));
        }
    }
}
