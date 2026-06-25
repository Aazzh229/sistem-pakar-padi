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
                'deskripsi' => $gd[3],
                'created_by' => $pakar->id
            ]);
        }

        // 3. Seed Penyakit
        $penyakitData = [
            ['P01', 'Blast Daun', 'Penyakit jamur Pyricularia oryzae yang menyerang daun padi.'],
            ['P02', 'Blast Leher', 'Penyakit blast yang menyerang leher malai sehingga malai patah dan bulir hampa.'],
            ['P03', 'Tungro', 'Penyakit virus yang menyebabkan tanaman kerdil dan daun menguning.'],
            ['P04', 'Hawar Daun Bakteri (HDB)', 'Penyakit bakteri Xanthomonas oryzae pv. oryzae.'],
            ['P05', 'Bercak Coklat', 'Penyakit jamur Bipolaris oryzae yang menyebabkan bercak coklat pada daun.'],
            ['P06', 'HDB Fase Lanjut', 'Tahap lanjut penyakit Hawar Daun Bakteri.'],
            ['P07', 'Gangguan Fisiologis', 'Gangguan akibat kekurangan unsur hara atau kondisi lingkungan.'],
        ];

        $penyakitModels = [];
        foreach ($penyakitData as $pd) {
            $penyakitModels[$pd[0]] = Penyakit::create([
                'kode_penyakit' => $pd[0],
                'nama_penyakit' => $pd[1],
                'slug' => Str::slug($pd[1]),
                'deskripsi' => $pd[2],
                'created_by' => $pakar->id
            ]);
        }

        // 4. Seed Hama
        $hamaData = [
            ['H01', 'Tikus Sawah', 'Hama pengerat yang memotong batang padi.'],
            ['H02', 'Wereng Coklat', 'Hama penghisap cairan batang padi.'],
            ['H03', 'Sundep', 'Serangan penggerek batang pada fase vegetatif.'],
            ['H04', 'Beluk', 'Serangan penggerek batang pada fase generatif.'],
            ['H05', 'Keong Mas', 'Siput air yang menyerang tanaman muda.'],
            ['H06', 'Ulat Daun', 'Larva yang memakan daun padi hingga menggulung.'],
            ['H07', 'Walang Sangit', 'Serangga penghisap bulir padi.'],
            ['H08', 'Ganjur', 'Hama pembentuk puru pada anakan padi.'],
        ];

        $hamaModels = [];
        foreach ($hamaData as $hd) {
            $hamaModels[$hd[0]] = Hama::create([
                'kode_hama' => $hd[0],
                'nama_hama' => $hd[1],
                'slug' => Str::slug($hd[1]),
                'deskripsi' => $hd[2],
                'created_by' => $pakar->id
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

        // 6. Seed Library (Default Initializer)
        $libraryData = [
            [
                'jenis' => 'penyakit',
                'nama' => 'Blast Padi',
                'nama_latin' => 'Pyricularia oryzae',
                'deskripsi' => 'Penyakit jamur yang menyerang daun, pelepah, dan malai padi.',
                'penyebab' => 'Jamur Pyricularia oryzae',
                'solusi' => 'Gunakan varietas tahan penyakit, kurangi pemakaian pupuk nitrogen berlebih, dan semprot fungisida.',
                'pencegahan' => 'Pengaturan jarak tanam legowo dan pembersihan sisa-sisa jerami padi.',
                'gambar' => 'https://agrokomplekskita.com/wp-content/uploads/2019/02/gejala-padi-kena-blas.jpg'
            ],
            [
                'jenis' => 'penyakit',
                'nama' => 'Tungro',
                'nama_latin' => 'Rice tungro virus',
                'deskripsi' => 'Penyakit virus ditularkan oleh wereng hijau, menyebabkan tanaman kerdil dan daun kuning jingga.',
                'penyebab' => 'Virus Tungro (RTBV dan RTSV) ditularkan oleh Wereng Hijau',
                'solusi' => 'Kendalikan vektor wereng hijau dengan insektisida sistemik dan cabut tanaman sakit.',
                'pencegahan' => 'Tanam serempak untuk memutus siklus hidup wereng hijau.',
                'gambar' => 'https://www.kampustani.com/wp-content/uploads/2022/07/cara-mengatasi-penyakit-tungro-pada-padi.jpg'
            ],
            [
                'jenis' => 'penyakit',
                'nama' => 'Hawar Daun Bakteri',
                'nama_latin' => 'Xanthomonas oryzae pv. oryzae',
                'deskripsi' => 'Penyakit bakteri menyerang daun padi, ditandai garis basah kekuningan yang mengering.',
                'penyebab' => 'Bakteri Xanthomonas oryzae pv. oryzae',
                'solusi' => 'Kurangi pengairan berlebih, hindari pupuk N berlebih, and semprot bakterisida.',
                'pencegahan' => 'Gunakan varietas tahan seperti Inpari dan pengairan berselang.',
                'gambar' => 'https://content.peat-cloud.com/thumbnails/bacterial-blight-of-rice-rice-1581498605.jpg'
            ],
            [
                'jenis' => 'penyakit',
                'nama' => 'Bercak Coklat',
                'nama_latin' => 'Helminthosporium oryzae',
                'deskripsi' => 'Infeksi jamur menyebabkan bercak oval coklat di permukaan daun padi.',
                'penyebab' => 'Jamur Helminthosporium oryzae / Bipolaris oryzae',
                'solusi' => 'Berikan pupuk kalium cukup, pemupukan berimbang, dan fungisida.',
                'pencegahan' => 'Perbaiki drainase tanah dan gunakan benih bebas patogen.',
                'gambar' => 'http://www.knowledgebank.irri.org/images/stories/brown-spot-3.jpg'
            ],
            [
                'jenis' => 'penyakit',
                'nama' => 'Busuk Pelepah',
                'nama_latin' => 'Rhizoctonia solani',
                'deskripsi' => 'Penyakit jamur merusak pelepah daun padi, ditandai bercak abu-abu kehijauan tidak beraturan.',
                'penyebab' => 'Jamur Rhizoctonia solani',
                'solusi' => 'Kurangi pupuk N berlebih, perbaiki drainase sawah, dan semprot fungisida.',
                'pencegahan' => 'Lakukan sanitasi gulma di sekitar sawah dan atur jarak tanam.',
                'gambar' => 'https://content.peat-cloud.com/thumbnails/sheath-rot-of-rice-rice-1553620104.jpg'
            ],
            [
                'jenis' => 'hama',
                'nama' => 'Tikus Sawah',
                'nama_latin' => 'Rattus argentiventer',
                'deskripsi' => 'Hama pengerat merusak padi pada semua fase tumbuh dengan memotong pangkal batang.',
                'penyebab' => 'Tikus Sawah (Rattus argentiventer)',
                'solusi' => 'Gropyokan massal, pasang Trap Barrier System (TBS), dan rodentisida.',
                'pencegahan' => 'Pembersihan pematang dan tanggul sawah agar tidak jadi sarang.',
                'gambar' => 'https://agrokomplekskita.com/wp-content/uploads/2017/04/tikus.jpg'
            ],
            [
                'jenis' => 'hama',
                'nama' => 'Wereng Coklat',
                'nama_latin' => 'Nilaparvata lugens',
                'deskripsi' => 'Hama penghisap cairan batang menyebabkan padi cepat menguning dan kering (hopperburn).',
                'penyebab' => 'Wereng Batang Coklat (Nilaparvata lugens)',
                'solusi' => 'Gunakan agens hayati, semprot insektisida pymetrozine jika populasi tinggi.',
                'pencegahan' => 'Jarak tanam legowo dan hindari insektisida spektrum luas di awal musim.',
                'gambar' => 'https://diperpa.badungkab.go.id/storage/diperpa/image/section-2-mobile-key-visual-kontrol-wereng-coklat.jpg'
            ],
            [
                'jenis' => 'hama',
                'nama' => 'Penggerek Batang',
                'nama_latin' => 'Scirpophaga innotata',
                'deskripsi' => 'Larva ngengat menyerang batang menimbulkan kematian tunas (sundep) atau malai hampa (beluk).',
                'penyebab' => 'Ulat Penggerek Batang Padi (Scirpophaga spp.)',
                'solusi' => 'Kumpulkan kelompok telur, gunakan light trap, dan insektisida sistemik.',
                'pencegahan' => 'Tanam serempak dan pembajakan tanah pasca panen.',
                'gambar' => 'https://gkmdblog.s3.ap-southeast-1.amazonaws.com/wp-content/uploads/2024/06/10101855/Blog-Penggerek-Batang-Padi.jpeg'
            ],
            [
                'jenis' => 'hama',
                'nama' => 'Keong Mas',
                'nama_latin' => 'Pomacea canaliculata',
                'deskripsi' => 'Siput air tawar memakan batang dan daun muda tanaman padi yang baru ditanam.',
                'penyebab' => 'Keong Mas (Pomacea canaliculata)',
                'solusi' => 'Kumpulkan keong manual, atur pintu air, atau taburkan moluskisida.',
                'pencegahan' => 'Pasang saringan kawat pada pintu air irigasi masuk sawah.',
                'gambar' => 'https://katalogcba.com/wp-content/uploads/2021/10/keong-mas.jpeg'
            ],
            [
                'jenis' => 'hama',
                'nama' => 'Walang Sangit',
                'nama_latin' => 'Leptocorisa oratorius',
                'deskripsi' => 'Serangga penghisap bulir padi fase masak susu menimbulkan bulir hampa dan bernoda.',
                'penyebab' => 'Walang Sangit (Leptocorisa oratorius)',
                'solusi' => 'Semprot insektisida kontak di pagi/sore hari saat serangga aktif.',
                'pencegahan' => 'Sanitasi gulma pematang sawah yang menjadi inang alternatif.',
                'gambar' => 'https://gkmdblog.s3.ap-southeast-1.amazonaws.com/wp-content/uploads/2023/12/14162626/Blog-banner-_-Inilah-Cara-Efektif-dalam-Pengendalian-Hama-Walang-Sangit-.jpg'
            ]
        ];

        // Seed initial library items
        foreach ($libraryData as $ld) {
            Library::create(array_merge($ld, ['created_by' => $pakar->id]));
        }

        // 7. Parse Excel file & update / append library database
        $excelPath = base_path('Tabel_Solusi_PHT_Sederhana versi diisi semua.xlsx');
        if (file_exists($excelPath)) {
            $zip = new \ZipArchive();
            if ($zip->open($excelPath) === TRUE) {
                // Read Shared Strings
                $sharedStrings = [];
                $sharedStringsEntry = $zip->getFromName('xl/sharedStrings.xml');
                if ($sharedStringsEntry) {
                    $xmlStrings = simplexml_load_string($sharedStringsEntry);
                    foreach ($xmlStrings->si as $si) {
                        if (isset($si->t)) {
                            $sharedStrings[] = (string)$si->t;
                        } else {
                            $text = '';
                            foreach ($si->r as $r) {
                                $text .= (string)$r->t;
                            }
                            $sharedStrings[] = $text;
                        }
                    }
                }

                // Read Sheet1
                $sheet1Entry = $zip->getFromName('xl/worksheets/sheet1.xml');
                if ($sheet1Entry) {
                    $xmlSheet = simplexml_load_string($sheet1Entry);
                    
                    // Mapping Excel rows
                    $excelMapping = [
<<<<<<< HEAD
                        'Penyakit Blast Daun' => [
                            'jenis' => 'penyakit',
                            'nama' => 'Blast Daun',
                            'nama_latin' => 'Pyricularia oryzae',
                            'penyebab' => 'Jamur Pyricularia oryzae',
                            'gambar' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=400'
                        ],

                        'Penyakit Blast Leher (Neck Blast)' => [
                            'jenis' => 'penyakit',
                            'nama' => 'Blast Leher',
                            'nama_latin' => 'Pyricularia oryzae',
                            'penyebab' => 'Jamur Pyricularia oryzae',
                            'gambar' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=400'
                        ],

                        'Hama Tikus' => [
                            'jenis' => 'hama',
                            'nama' => 'Tikus Sawah',
                            'nama_latin' => 'Rattus argentiventer',
                            'penyebab' => 'Tikus Sawah (Rattus argentiventer)',
                            'gambar' => 'https://images.unsplash.com/photo-1428908728789-d2de25dbd4e2?w=400'
                        ],

                        'Hama Wereng Coklat' => [
                            'jenis' => 'hama',
                            'nama' => 'Wereng Coklat',
                            'nama_latin' => 'Nilaparvata lugens',
                            'penyebab' => 'Wereng Batang Coklat (Nilaparvata lugens)',
                            'gambar' => 'https://images.unsplash.com/photo-1508849789987-4e5333c12b78?w=400'
                        ],

                        'Penyakit Tungro' => [
                            'jenis' => 'penyakit',
                            'nama' => 'Tungro',
                            'nama_latin' => 'Rice tungro virus',
                            'penyebab' => 'Virus Tungro (RTBV dan RTSV)',
                            'gambar' => 'https://images.unsplash.com/photo-1628352081506-83c43123ed6d?w=400'
                        ],

                        'Hama Sundep (Penggerek Batang Fase Vegetatif)' => [
                            'jenis' => 'hama',
                            'nama' => 'Sundep',
                            'nama_latin' => 'Scirpophaga innotata',
                            'penyebab' => 'Ulat Penggerek Batang Padi',
                            'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400'
                        ],

                        'Hama Beluk (Penggerek Batang Fase Generatif)' => [
                            'jenis' => 'hama',
                            'nama' => 'Beluk',
                            'nama_latin' => 'Scirpophaga innotata',
                            'penyebab' => 'Ulat Penggerek Batang Padi',
                            'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400'
                        ],

                        'Hama Keong Mas' => [
                            'jenis' => 'hama',
                            'nama' => 'Keong Mas',
                            'nama_latin' => 'Pomacea canaliculata',
                            'penyebab' => 'Keong Mas (Pomacea canaliculata)',
                            'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400'
                        ],

                        'Hawar Daun Bakteri (HDB) / Kresek' => [
                            'jenis' => 'penyakit',
                            'nama' => 'Hawar Daun Bakteri (HDB)',
                            'nama_latin' => 'Xanthomonas oryzae pv. oryzae',
                            'penyebab' => 'Bakteri Xanthomonas oryzae pv. oryzae',
                            'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400'
                        ],

                        'Hama Putih / Ulat Daun' => [
                            'jenis' => 'hama',
                            'nama' => 'Hama Putih / Ulat Daun',
                            'nama_latin' => 'Nymphula depunctalis',
                            'penyebab' => 'Ulat daun / Hama putih (Nymphula depunctalis)',
                            'gambar' => 'https://images.unsplash.com/photo-1428908728789-d2de25dbd4e2?w=400'
                        ],

                        'Hama Walang Sangit' => [
                            'jenis' => 'hama',
                            'nama' => 'Walang Sangit',
                            'nama_latin' => 'Leptocorisa oratorius',
                            'penyebab' => 'Walang Sangit (Leptocorisa oratorius)',
                            'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400'
                        ],

                        'Penyakit Bercak Coklat' => [
                            'jenis' => 'penyakit',
                            'nama' => 'Bercak Coklat',
                            'nama_latin' => 'Bipolaris oryzae',
                            'penyebab' => 'Jamur Bipolaris oryzae',
                            'gambar' => 'https://images.unsplash.com/photo-1628352081506-83c43123ed6d?w=400'
                        ],

                        'Hama Ganjur' => [
                            'jenis' => 'hama',
                            'nama' => 'Ganjur',
                            'nama_latin' => 'Orseolia oryzae',
                            'penyebab' => 'Tabuhan Ganjur (Orseolia oryzae)',
                            'gambar' => 'https://images.unsplash.com/photo-1508849789987-4e5333c12b78?w=400'
                        ],

                        'Serangan Hama Wereng Berat (Puso)' => [
                            'jenis' => 'hama',
                            'nama' => 'Wereng Berat (Puso)',
                            'nama_latin' => 'Nilaparvata lugens',
                            'penyebab' => 'Wereng Batang Coklat (Nilaparvata lugens)',
                            'gambar' => 'https://images.unsplash.com/photo-1508849789987-4e5333c12b78?w=400'
                        ],

                        'HDB Fase Lanjut' => [
                            'jenis' => 'penyakit',
                            'nama' => 'HDB Fase Lanjut',
                            'nama_latin' => 'Xanthomonas oryzae pv. oryzae',
                            'penyebab' => 'Bakteri Xanthomonas oryzae pv. oryzae',
                            'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400'
                        ],
=======
                        'Penyakit Blast Daun' => ['jenis' => 'penyakit', 'nama' => 'Blast Padi', 'nama_latin' => 'Pyricularia oryzae', 'penyebab' => 'Jamur Pyricularia oryzae', 'gambar' => 'https://agrokomplekskita.com/wp-content/uploads/2019/02/gejala-padi-kena-blas.jpg'],
                        'Penyakit Blast Leher (Neck Blast)' => ['jenis' => 'penyakit', 'nama' => 'Blast Padi', 'nama_latin' => 'Pyricularia oryzae', 'penyebab' => 'Jamur Pyricularia oryzae', 'gambar' => 'https://agrokomplekskita.com/wp-content/uploads/2019/02/gejala-padi-kena-blas.jpg'],
                        'Hama Tikus' => ['jenis' => 'hama', 'nama' => 'Tikus Sawah', 'nama_latin' => 'Rattus argentiventer', 'penyebab' => 'Tikus Sawah (Rattus argentiventer)', 'gambar' => 'https://agrokomplekskita.com/wp-content/uploads/2017/04/tikus.jpg'],
                        'Hama Wereng Coklat' => ['jenis' => 'hama', 'nama' => 'Wereng Coklat', 'nama_latin' => 'Nilaparvata lugens', 'penyebab' => 'Wereng Batang Coklat (Nilaparvata lugens)', 'gambar' => 'https://diperpa.badungkab.go.id/storage/diperpa/image/section-2-mobile-key-visual-kontrol-wereng-coklat.jpg'],
                        'Penyakit Tungro' => ['jenis' => 'penyakit', 'nama' => 'Tungro', 'nama_latin' => 'Rice tungro virus', 'penyebab' => 'Virus Tungro (RTBV dan RTSV)', 'gambar' => 'https://www.kampustani.com/wp-content/uploads/2022/07/cara-mengatasi-penyakit-tungro-pada-padi.jpg'],
                        'Hama Sundep (Penggerek Batang Fase Vegetatif)' => ['jenis' => 'hama', 'nama' => 'Penggerek Batang', 'nama_latin' => 'Scirpophaga innotata', 'penyebab' => 'Ulat Penggerek Batang Padi', 'gambar' => 'https://gkmdblog.s3.ap-southeast-1.amazonaws.com/wp-content/uploads/2024/06/10101855/Blog-Penggerek-Batang-Padi.jpeg'],
                        'Hama Beluk (Penggerek Batang Fase Generatif)' => ['jenis' => 'hama', 'nama' => 'Penggerek Batang', 'nama_latin' => 'Scirpophaga innotata', 'penyebab' => 'Ulat Penggerek Batang Padi', 'gambar' => 'https://gkmdblog.s3.ap-southeast-1.amazonaws.com/wp-content/uploads/2024/06/10101855/Blog-Penggerek-Batang-Padi.jpeg'],
                        'Hama Keong Mas' => ['jenis' => 'hama', 'nama' => 'Keong Mas', 'nama_latin' => 'Pomacea canaliculata', 'penyebab' => 'Keong Mas (Pomacea canaliculata)', 'gambar' => 'https://katalogcba.com/wp-content/uploads/2021/10/keong-mas.jpeg'],
                        'Hawar Daun Bakteri (HDB) / Kresek' => ['jenis' => 'penyakit', 'nama' => 'Hawar Daun Bakteri', 'nama_latin' => 'Xanthomonas oryzae pv. oryzae', 'penyebab' => 'Bakteri Xanthomonas oryzae pv. oryzae', 'gambar' => 'https://content.peat-cloud.com/thumbnails/bacterial-blight-of-rice-rice-1581498605.jpg'],
                        'Hama Putih / Ulat Daun' => ['jenis' => 'hama', 'nama' => 'Hama Putih / Ulat Daun', 'nama_latin' => 'Nymphula depunctalis', 'penyebab' => 'Ulat daun / Hama putih (Nymphula depunctalis)', 'gambar' => 'https://portal.merauke.go.id/api/serve-uploads/images/Foto-ilustrasi.jpeg'],
                        'Hama Walang Sangit' => ['jenis' => 'hama', 'nama' => 'Walang Sangit', 'nama_latin' => 'Leptocorisa oratorius', 'penyebab' => 'Walang Sangit (Leptocorisa oratorius)', 'gambar' => 'https://gkmdblog.s3.ap-southeast-1.amazonaws.com/wp-content/uploads/2023/12/14162626/Blog-banner-_-Inilah-Cara-Efektif-dalam-Pengendalian-Hama-Walang-Sangit-.jpg'],
                        'Penyakit Bercak Coklat' => ['jenis' => 'penyakit', 'nama' => 'Bercak Coklat', 'nama_latin' => 'Bipolaris oryzae', 'penyebab' => 'Jamur Bipolaris oryzae', 'gambar' => 'http://www.knowledgebank.irri.org/images/stories/brown-spot-3.jpg'],
                        'Hama Ganjur' => ['jenis' => 'hama', 'nama' => 'Hama Ganjur', 'nama_latin' => 'Orseolia oryzae', 'penyebab' => 'Tabuhan Ganjur (Orseolia oryzae)', 'gambar' => 'https://assets.promediateknologi.id/crop/0x0:0x0/750x500/webp/photo/p2/23/2023/09/28/Foto-Berita-Website-CariAku-2023-09-28T151609660-3551889010.png'],
                        'Serangan Hama Wereng Berat (Puso)' => ['jenis' => 'hama', 'nama' => 'Wereng Coklat', 'nama_latin' => 'Nilaparvata lugens', 'penyebab' => 'Wereng Batang Coklat (Nilaparvata lugens)', 'gambar' => 'https://diperpa.badungkab.go.id/storage/diperpa/image/section-2-mobile-key-visual-kontrol-wereng-coklat.jpg'],
                        'HDB Fase Lanjut' => ['jenis' => 'penyakit', 'nama' => 'Hawar Daun Bakteri', 'nama_latin' => 'Xanthomonas oryzae pv. oryzae', 'penyebab' => 'Bakteri Xanthomonas oryzae pv. oryzae', 'gambar' => 'https://content.peat-cloud.com/thumbnails/bacterial-blight-of-rice-rice-1581498605.jpg'],
>>>>>>> 0efd90bf317fa6c8e2dda4870f41348d4fa0a2e7
                    ];

                    foreach ($xmlSheet->sheetData->row as $row) {
                        $rowIndex = (int)$row['r'];
                        if ($rowIndex < 6) continue;

                        $rowData = [];
                        foreach ($row->c as $c) {
                            $r = (string)$c['r'];
                            $col = preg_replace('/[0-9]/', '', $r);
                            $val = '';
                            if (isset($c->v)) {
                                $val = (string)$c->v;
                                if (isset($c['t']) && (string)$c['t'] === 's') {
                                    $val = $sharedStrings[(int)$val] ?? '';
                                }
                            }
                            $rowData[$col] = trim($val);
                        }

                        $excelName = $rowData['C'] ?? '';
                        $phtText = $rowData['D'] ?? '';

                        if (empty($excelName) || empty($phtText) || !isset($excelMapping[$excelName])) {
                            continue;
                        }

                        $mapping = $excelMapping[$excelName];
                        
                        // Simple keyword-based parsing of PHT solution text
                        $solusiKeywords = ['Kultur Teknis', 'Hayati', 'Kimiawi', 'Mekanis', 
                            'Fisik', 'Nabati', 'Pemupukan', 'Pengelolaan Air', 
                            'Pengendalian Vektor', 'Kuratif kimiawi', 'Tindakan Kuratif',
                            'Manajemen Nutrisi', 'Kimiawi (Tindakan Penahanan)'];
                        $pencegahanKeywords = ['Preventif', 'Profilaksis', 'Sanitasi'];
                        
                        // Collect all lines
                        $lines = explode("\n", $phtText);
                        $solusiLines = [];
                        $pencegahanLines = [];
                        $descLines = [];
                        
                        foreach ($lines as $line) {
                            $trimmed = trim($line);
                            if (empty($trimmed)) continue;
                            
                            $isPencegahan = false;
                            foreach ($pencegahanKeywords as $kw) {
                                if (stripos($trimmed, $kw . ':') === 0) {
                                    $isPencegahan = true;
                                    break;
                                }
                            }
                            
                            if ($isPencegahan) {
                                $pencegahanLines[] = $trimmed;
                            } elseif (stripos($trimmed, 'Konteks:') === 0) {
                                $descLines[] = $trimmed;
                            } else {
                                $solusiLines[] = $trimmed;
                            }
                        }

                        $parsedSolusi = implode("\n", $solusiLines);
                        $parsedPencegahan = implode("\n", $pencegahanLines);
                        $parsedDesc = implode("\n", $descLines);


                        // Find existing Library item
                        $libraryItem = Library::where('jenis', $mapping['jenis'])
                            ->where('nama', $mapping['nama'])
                            ->first();

                        if ($libraryItem) {
                            $existingSolusi = $libraryItem->solusi;
                            $existingPencegahan = $libraryItem->pencegahan;
                            
                            if (strpos($existingSolusi, $excelName) === false) {
                                $updatedSolusi = $existingSolusi . "\n\n**Pengendalian (" . $excelName . "):**\n" . $parsedSolusi;
                                $updatedPencegahan = $existingPencegahan;
                                if (!empty($parsedPencegahan)) {
                                    $updatedPencegahan = $existingPencegahan . "\n\n**Pencegahan (" . $excelName . "):**\n" . $parsedPencegahan;
                                }
                                
                                $libraryItem->update([
                                    'solusi' => $updatedSolusi,
                                    'pencegahan' => $updatedPencegahan,
                                ]);
                            }
                        } else {
                            Library::create([
                                'jenis' => $mapping['jenis'],
                                'nama' => $mapping['nama'],
                                'nama_latin' => $mapping['nama_latin'],
                                'deskripsi' => $parsedDesc ?: 'Edukasi dan solusi PHT untuk ' . $mapping['nama'],
                                'penyebab' => $mapping['penyebab'],
                                'solusi' => $parsedSolusi,
                                'pencegahan' => $parsedPencegahan ?: 'Lakukan sanitasi lingkungan secara teratur.',
                                'gambar' => $mapping['gambar'],
                                'created_by' => $pakar->id
                            ]);
                        }
                    }
                }
                $zip->close();
            }
        }
    }
}
