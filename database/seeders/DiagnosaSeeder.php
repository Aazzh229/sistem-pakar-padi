<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Diagnosa;

class DiagnosaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'P01' => [
                'nama_diagnosa' => 'Blast Padi',
                'nama_latin' => 'Pyricularia oryzae',
                'deskripsi' => 'Penyakit jamur yang menyerang daun, batang, dan malai padi.',
                'solusi' => 'Gunakan varietas tahan penyakit, kurangi pemakaian pupuk nitrogen berlebih, dan semprot fungisida berbahan aktif trisiklazol bila intensitas serangan tinggi.',
                'penyebab' => 'Jamur Pyricularia oryzae',
                'pencegahan' => 'Pengaturan jarak tanam legowo dan pembersihan sisa-sisa jerami padi yang terinfeksi.',
                'gambar' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=400&auto=format&fit=crop&q=80'
            ],
            'P02' => [
                'nama_diagnosa' => 'Blast Leher',
                'nama_latin' => 'Pyricularia oryzae',
                'deskripsi' => 'Serangan penyakit blast leher pada malai padi yang menyebabkan pembusukan leher malai.',
                'solusi' => 'Semprot fungisida sistemik sebelum tanaman berbunga dan kurangi kelembapan sawah.',
                'penyebab' => 'Jamur Pyricularia oryzae',
                'pencegahan' => 'Gunakan benih sehat berkualitas dan hindari tanam terlalu rapat.',
                'gambar' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=400&auto=format&fit=crop&q=80'
            ],
            'P03' => [
                'nama_diagnosa' => 'Tungro',
                'nama_latin' => 'Rice tungro bacilliform virus',
                'deskripsi' => 'Penyakit virus yang ditularkan oleh wereng hijau, menyebabkan tanaman padi kerdil dan daun berwarna kuning jingga.',
                'solusi' => 'Kendalikan vektor wereng hijau dengan insektisida sistemik dan musnahkan tanaman yang terinfeksi berat.',
                'penyebab' => 'Virus Tungro (RTBV dan RTSV)',
                'pencegahan' => 'Tanam serempak untuk memutus siklus hidup wereng hijau.',
                'gambar' => 'https://images.unsplash.com/photo-1628352081506-83c43123ed6d?w=400&auto=format&fit=crop&q=80'
            ],
            'P04' => [
                'nama_diagnosa' => 'Hawar Daun Bakteri',
                'nama_latin' => 'Xanthomonas oryzae pv. oryzae',
                'deskripsi' => 'Penyakit bakteri yang menyerang daun padi, ditandai dengan garis basah pada helai daun yang mengering seperti terbakar.',
                'solusi' => 'Kurangi pengairan berlebih, hindari pemupukan N yang berlebih, dan aplikasikan bakterisida.',
                'penyebab' => 'Bakteri Xanthomonas oryzae pv. oryzae',
                'pencegahan' => 'Gunakan varietas tahan seperti Inpari, dan gunakan sistem pengairan berselang (intermittent).',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ],
            'P05' => [
                'nama_diagnosa' => 'Bercak Coklat',
                'nama_latin' => 'Helminthosporium oryzae',
                'deskripsi' => 'Infeksi jamur yang menyebabkan bercak oval berwarna coklat pada permukaan daun padi.',
                'solusi' => 'Berikan pupuk kalium (K) yang cukup, lakukan pemupukan berimbang, dan gunakan fungisida jika diperlukan.',
                'penyebab' => 'Jamur Helminthosporium oryzae',
                'pencegahan' => 'Perbaiki drainase tanah dan gunakan benih bebas patogen.',
                'gambar' => 'https://images.unsplash.com/photo-1628352081506-83c43123ed6d?w=400&auto=format&fit=crop&q=80'
            ],
            'P06' => [
                'nama_diagnosa' => 'HDB Fase Lanjut',
                'nama_latin' => 'Xanthomonas oryzae pv. oryzae',
                'deskripsi' => 'Tahap lanjut penyakit Hawar Daun Bakteri (Kresek) yang menyebabkan seluruh daun mengering dan tanaman mati.',
                'solusi' => 'Cabut dan bakar tanaman yang terserang kresek parah, kurangi air di petakan sawah.',
                'penyebab' => 'Bakteri Xanthomonas oryzae pv. oryzae',
                'pencegahan' => 'Sanitasi lingkungan dan hindari melukai bibit padi saat pindah tanam.',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ],
            'P07' => [
                'nama_diagnosa' => 'Busuk Pelepah',
                'nama_latin' => 'Rhizoctonia solani',
                'deskripsi' => 'Penyakit jamur yang merusak pelepah daun padi, ditandai dengan bercak bulat tidak beraturan berwarna abu-abu kehijauan.',
                'solusi' => 'Kurangi pemakaian pupuk N berlebih, perbaiki drainase sawah, dan semprot fungisida berbahan aktif dipenokonazol jika parah.',
                'penyebab' => 'Jamur Rhizoctonia solani',
                'pencegahan' => 'Lakukan sanitasi gulma di sekitar sawah dan atur jarak tanam.',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ],
            'H01' => [
                'nama_diagnosa' => 'Tikus Sawah',
                'nama_latin' => 'Rattus argentiventer',
                'deskripsi' => 'Hama pengerat yang merusak tanaman padi pada semua fase pertumbuhan dengan memotong batang padi.',
                'solusi' => 'Lakukan gropyokan massal, pasang TBS (Trap Barrier System), atau gunakan rodentisida.',
                'penyebab' => 'Tikus Sawah (Rattus argentiventer)',
                'pencegahan' => 'Pembersihan semak-semak dan tanggul sawah agar tidak menjadi sarang tikus.',
                'gambar' => 'https://images.unsplash.com/photo-1428908728789-d2de25dbd4e2?w=400&auto=format&fit=crop&q=80'
            ],
            'H02' => [
                'nama_diagnosa' => 'Wereng Coklat',
                'nama_latin' => 'Nilaparvata lugens',
                'deskripsi' => 'Hama penghisap cairan batang padi yang paling merusak, menyebabkan tanaman mengering kecoklatan (hopperburn).',
                'solusi' => 'Gunakan musuh alami seperti laba-laba, semprot insektisida berbahan aktif pymetrozine jika populasi ambang batas terlampaui.',
                'penyebab' => 'Wereng Batang Coklat (Nilaparvata lugens)',
                'pencegahan' => 'Hindari penggunaan insektisida spektrum luas di awal musim tanam.',
                'gambar' => 'https://images.unsplash.com/photo-1508849789987-4e5333c12b78?w=400&auto=format&fit=crop&q=80'
            ],
            'H03' => [
                'nama_diagnosa' => 'Penggerek Batang',
                'nama_latin' => 'Scirpophaga innotata',
                'deskripsi' => 'Larva ngengat penggerek batang padi yang menyerang pada fase vegetatif menyebabkan anakan mati (sundep).',
                'solusi' => 'Kumpulkan telur penggerek di lapangan, pasang perangkap lampu (light trap), semprot insektisida sistemik.',
                'penyebab' => 'Ulat Penggerek Batang Padi (Scirpophaga innotata / incertulas)',
                'pencegahan' => 'Tanam serentak dan lakukan pembajakan tanah sawah setelah panen untuk membunuh larva di jerami.',
                'gambar' => 'placeholder'
            ],
            'H04' => [
                'nama_diagnosa' => 'Hama Beluk',
                'nama_latin' => 'Scirpophaga incertulas',
                'deskripsi' => 'Larva ngengat penggerek batang padi yang menyerang pada fase generatif menyebabkan malai putih hampa (beluk).',
                'solusi' => 'Gunakan insektisida sistemik granul (tabur) pada tanah saat fase bunting.',
                'penyebab' => 'Ulat Penggerek Batang Padi (Scirpophaga incertulas)',
                'pencegahan' => 'Gunakan perangkap feromon untuk menangkap ngengat jantan.',
                'gambar' => 'placeholder'
            ],
            'H05' => [
                'nama_diagnosa' => 'Keong Mas',
                'nama_latin' => 'Pomacea canaliculata',
                'deskripsi' => 'Siput air tawar yang memakan tunas-tunas padi muda yang baru ditanam pada sawah yang tergenang.',
                'solusi' => 'Kumpulkan keong secara manual, buat parit kecil di keliling petakan sawah, atau taburkan moluskisida.',
                'penyebab' => 'Keong Mas (Pomacea canaliculata)',
                'pencegahan' => 'Pasang saringan pada pintu air masuk sawah agar bayi keong tidak masuk.',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ],
            'H06' => [
                'nama_diagnosa' => 'Hama Ulat Daun',
                'nama_latin' => 'Cnaphalocrocis medinalis',
                'deskripsi' => 'Larva ulat bulu atau ulat grayak yang melipat daun dan memakan epidermis daun padi.',
                'solusi' => 'Semprot dengan insektisida kontak berbahan aktif deltametrin.',
                'penyebab' => 'Ulat Daun Padi (Cnaphalocrocis medinalis)',
                'pencegahan' => 'Kurangi penggunaan nitrogen berlebih agar daun tidak terlalu lunak dan disukai ulat.',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ],
            'H07' => [
                'nama_diagnosa' => 'Walang Sangit',
                'nama_latin' => 'Leptocorisa oratorius',
                'deskripsi' => 'Serangga penghisap bulir padi muda (fase masak susu) yang mengeluarkan bau busuk menyengat.',
                'solusi' => 'Semprot insektisida kontak di pagi hari ketika walang sangit berkumpul di bagian atas tanaman.',
                'penyebab' => 'Walang Sangit (Leptocorisa oratorius)',
                'pencegahan' => 'Bersihkan rumput-rumput liar di pematang sawah yang menjadi inang alternatif.',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ],
            'H08' => [
                'nama_diagnosa' => 'Hama Ganjur',
                'nama_latin' => 'Orseolia oryzae',
                'deskripsi' => 'Lalat ganjur yang menyerang tunas tumbuh padi sehingga tunas tumbuh abnormal seperti daun bawang.',
                'solusi' => 'Gunakan insektisida tabur yang meresap secara sistemik pada perakaran tanaman.',
                'penyebab' => 'Lalat Ganjur Padi (Orseolia oryzae)',
                'pencegahan' => 'Tanam lebih awal dan berikan pemupukan berimbang.',
                'gambar' => 'https://images.unsplash.com/photo-1530595467537-0b5996c41f2d?w=400&auto=format&fit=crop&q=80'
            ]
        ];

        foreach ($data as $kode => $attributes) {
            Diagnosa::where('kode_diagnosa', $kode)->update($attributes);
        }
    }
}
