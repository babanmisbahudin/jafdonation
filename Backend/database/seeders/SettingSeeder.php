<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // ── SETTINGS ──────────────────────────────────────────────
        $settings = [
            // General
            ['key' => 'site_name',        'label' => 'Nama Website',          'type' => 'text',     'group' => 'general', 'value' => 'Jatiwangi Art Factory'],
            ['key' => 'site_tagline',     'label' => 'Tagline',               'type' => 'text',     'group' => 'general', 'value' => 'Yayasan Daun Salambar'],
            ['key' => 'site_description', 'label' => 'Deskripsi Website',     'type' => 'textarea', 'group' => 'general', 'value' => 'Komunitas seni kontemporer berbasis bumi di Jatiwangi, Majalengka.'],
            ['key' => 'site_logo',        'label' => 'Logo Website',          'type' => 'image',    'group' => 'general', 'value' => null],

            // Mission
            ['key' => 'mission_title',    'label' => 'Judul Section Misi',    'type' => 'text',     'group' => 'mission', 'value' => 'Seni Memiliki Vitalitas untuk Mengubah Daerah'],
            ['key' => 'mission_text_1',   'label' => 'Paragraf 1',            'type' => 'textarea', 'group' => 'mission', 'value' => 'Jatiwangi Art Factory (JAF) adalah komunitas seni kontemporer yang berbasis di Jatiwangi, Kabupaten Majalengka, Jawa Barat — berdiri sejak tahun 2005 di bawah naungan Yayasan Daun Salambar.'],
            ['key' => 'mission_text_2',   'label' => 'Paragraf 2',            'type' => 'textarea', 'group' => 'mission', 'value' => 'JAF berkomitmen membangun kapasitas masyarakat lokal melalui praktik seni berbasis bumi (earth-based art), residensi seniman, pameran internasional, dan program budaya komunal yang telah berjalan hampir dua dekade.'],
            ['key' => 'mission_text_3',   'label' => 'Paragraf 3',            'type' => 'textarea', 'group' => 'mission', 'value' => 'Dukungan Anda akan membantu JAF terus menggerakkan program Tahun Tanah, Rampak Genteng, Terracotta Triennale, dan Dana Hibah Budaya Daerah.'],
            ['key' => 'mission_photo',    'label' => 'Foto Section Misi',     'type' => 'image',    'group' => 'mission', 'value' => null],

            // Visi
            ['key' => 'visi_title',   'label' => 'Judul Section Visi', 'type' => 'text',     'group' => 'visi', 'value' => 'Visi Jatiwangi Art Factory'],
            ['key' => 'visi_text_1',  'label' => 'Paragraf 1',         'type' => 'textarea', 'group' => 'visi', 'value' => 'Menjadi pusat seni dan budaya komunal yang berdaya guna bagi masyarakat Jatiwangi dan Indonesia.'],
            ['key' => 'visi_text_2',  'label' => 'Paragraf 2',         'type' => 'textarea', 'group' => 'visi', 'value' => 'Membangun ekosistem seni yang berkelanjutan dengan mengakar pada tradisi lokal dan membuka diri terhadap dialog global.'],
            ['key' => 'visi_text_3',  'label' => 'Paragraf 3',         'type' => 'textarea', 'group' => 'visi', 'value' => null],
            ['key' => 'visi_photo',   'label' => 'Foto Section Visi',  'type' => 'image',    'group' => 'visi', 'value' => null],

            // Campaign
            ['key' => 'campaign_label',       'label' => 'Label Kampanye',         'type' => 'text',     'group' => 'campaign', 'value' => 'Residensi Seniman'],
            ['key' => 'campaign_title',       'label' => 'Judul Kampanye',         'type' => 'text',     'group' => 'campaign', 'value' => 'Ruang Berkarya untuk Seniman Dunia'],
            ['key' => 'campaign_quote',       'label' => 'Kutipan Kampanye',       'type' => 'textarea', 'group' => 'campaign', 'value' => 'Jatiwangi adalah laboratorium hidup di mana seni dan kehidupan sehari-hari tidak bisa dipisahkan.'],
            ['key' => 'campaign_text_1',      'label' => 'Teks Paragraf 1',        'type' => 'textarea', 'group' => 'campaign', 'value' => 'Program residensi JAF mengundang seniman lokal dan internasional untuk tinggal, belajar, dan berkarya bersama warga Jatiwangi.'],
            ['key' => 'campaign_text_2',      'label' => 'Teks Paragraf 2',        'type' => 'textarea', 'group' => 'campaign', 'value' => 'Dukungan Anda membantu JAF menyediakan fasilitas studio, penginapan, dan program residensi agar ekosistem seni Jatiwangi terus berkembang.'],
            ['key' => 'campaign_big_number',  'label' => 'Angka Besar',            'type' => 'text',     'group' => 'campaign', 'value' => '20+'],
            ['key' => 'campaign_number_label','label' => 'Label Angka',            'type' => 'text',     'group' => 'campaign', 'value' => 'Tahun Membangun Ekosistem Seni Lokal'],
            ['key' => 'campaign_btn_text',    'label' => 'Teks Tombol CTA',        'type' => 'text',     'group' => 'campaign', 'value' => 'Dukung Residensi Seniman'],
            ['key' => 'campaign_btn_url',     'label' => 'URL Tombol CTA',         'type' => 'text',     'group' => 'campaign', 'value' => 'pages/donasi.html'],
            ['key' => 'campaign_photo',       'label' => 'Foto Kampanye',          'type' => 'image',    'group' => 'campaign', 'value' => null],

            // Donate Card
            ['key' => 'donate_bank_name',     'label' => 'Nama Bank',              'type' => 'text',     'group' => 'donate',   'value' => 'Bank BJB'],
            ['key' => 'donate_account_number','label' => 'Nomor Rekening',         'type' => 'text',     'group' => 'donate',   'value' => '0118751892100'],
            ['key' => 'donate_account_name',  'label' => 'Nama Pemilik Rekening',  'type' => 'text',     'group' => 'donate',   'value' => 'Jatiwangi art Factory'],
            ['key' => 'donate_swift',         'label' => 'Kode SWIFT',             'type' => 'text',     'group' => 'donate',   'value' => 'PDJBIDJA'],
            ['key' => 'donate_min_amount',    'label' => 'Donasi Minimal',         'type' => 'text',     'group' => 'donate',   'value' => 'Rp 50.000'],
            ['key' => 'donate_qr_image',      'label' => 'Gambar QR Code',         'type' => 'image',    'group' => 'donate',   'value' => null],

            // General (Homepage Quote)
            ['key' => 'homepage_quote', 'label' => 'Kutipan di Bawah Video', 'type' => 'textarea', 'group' => 'general', 'value' => '"Seni memiliki vitalitas untuk mengubah suatu daerah, membangun kapasitas masyarakat, dan membawa denyut nadi komunitas lokal untuk beresonansi di panggung global."'],

            // Video YouTube
            ['key' => 'video_1_url',   'label' => 'URL YouTube Video 1', 'type' => 'text', 'group' => 'video', 'value' => ''],
            ['key' => 'video_1_title', 'label' => 'Judul Video 1',       'type' => 'text', 'group' => 'video', 'value' => ''],
            ['key' => 'video_2_url',   'label' => 'URL YouTube Video 2', 'type' => 'text', 'group' => 'video', 'value' => ''],
            ['key' => 'video_2_title', 'label' => 'Judul Video 2',       'type' => 'text', 'group' => 'video', 'value' => ''],
            ['key' => 'video_3_url',   'label' => 'URL YouTube Video 3', 'type' => 'text', 'group' => 'video', 'value' => ''],
            ['key' => 'video_3_title', 'label' => 'Judul Video 3',       'type' => 'text', 'group' => 'video', 'value' => ''],
        ];

        foreach ($settings as $data) {
            Setting::updateOrCreate(['key' => $data['key']], $data);
        }

        // Remove deprecated sections
        Setting::where('group', 'about')->delete();
        Setting::where('group', 'footer')->delete();

        // ── HERO SLIDES ───────────────────────────────────────────
        if (HeroSlide::count() === 0) {
            $slides = [
                [
                    'tag'         => 'Tahun Tanah — Triennale Seni & Budaya 2025',
                    'tag_color'   => '#E55A00',
                    'title_1'     => 'SENI UNTUK',
                    'title_2'     => 'KEHIDUPAN',
                    'description' => 'Jatiwangi Art Factory (JAF) adalah komunitas seni berbasis di Jatiwangi, Majalengka, yang berkomitmen mengubah daerah melalui praktik seni kontemporer berbasis bumi.',
                    'quote'       => '"Seni memiliki vitalitas untuk mengubah suatu daerah, membangun kapasitas masyarakat, dan membawa denyut nadi komunitas lokal untuk beresonansi di panggung global."',
                    'author'      => '— Jatiwangi Art Factory',
                    'bg_color'    => '#0a1f5e',
                    'cta_text'    => 'Dukung JAF',
                    'cta_url'     => 'pages/donasi.html',
                    'sort_order'  => 0,
                    'is_active'   => true,
                ],
                [
                    'tag'         => 'Rampak Genteng — Ritual Musik Komunal',
                    'tag_color'   => '#ffffff',
                    'title_1'     => 'TANAH UNTUK',
                    'title_2'     => 'SEMUA ORANG',
                    'description' => 'Rampak Genteng adalah ritual kolektif memainkan genteng atap sebagai instrumen musik, merayakan warisan budaya tanah liat Jatiwangi bersama warga.',
                    'quote'       => '"Tanah adalah ingatan, dan seni adalah cara kita berbicara dengannya."',
                    'author'      => '— Komunitas JAF',
                    'bg_color'    => '#005A2B',
                    'cta_text'    => 'Dukung Program',
                    'cta_url'     => 'pages/donasi.html',
                    'sort_order'  => 1,
                    'is_active'   => true,
                ],
                [
                    'tag'         => 'Terracotta Triennale — Pameran Seni Dunia',
                    'tag_color'   => '#FFD700',
                    'title_1'     => 'BERKARYA DARI',
                    'title_2'     => 'BUMI KITA',
                    'description' => 'Terracotta Triennale menghadirkan seniman lokal dan internasional yang berkolaborasi mengeksplorasi material tanah liat sebagai medium seni kontemporer.',
                    'quote'       => '"Dari tanah kita lahir, dari tanah kita berkarya, untuk tanah kita kembali."',
                    'author'      => '— Jatiwangi Art Factory, sejak 2005',
                    'bg_color'    => '#4a007a',
                    'cta_text'    => 'Lihat Pameran',
                    'cta_url'     => 'pages/galeri.html',
                    'sort_order'  => 2,
                    'is_active'   => true,
                ],
                [
                    'tag'         => 'Magister Reka Budaya — Program Akademik',
                    'tag_color'   => '#E55A00',
                    'title_1'     => 'BANGUN',
                    'title_2'     => 'BUDAYA LOKAL',
                    'description' => 'Program Magister Reka Budaya bersama Universitas Padjadjaran mengajak generasi muda merencanakan dan membangun ekosistem budaya daerah yang berkelanjutan.',
                    'quote'       => '"Komunitas yang kuat tumbuh dari akar budayanya sendiri."',
                    'author'      => '— Program Reka Budaya, JAF',
                    'bg_color'    => '#7b1416',
                    'cta_text'    => 'Ikuti Program',
                    'cta_url'     => 'pages/relawan.html',
                    'sort_order'  => 3,
                    'is_active'   => true,
                ],
            ];

            foreach ($slides as $slide) {
                HeroSlide::create($slide);
            }
        }
    }
}
