<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@jafdonation.id')],
            [
                'name'     => 'Administrator',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
                'role'     => 'admin',
                'is_active'=> true,
            ]
        );

        // Default categories
        $categories = [
            ['name' => 'Amal',                    'slug' => 'amal',       'color' => '#E74C3C', 'icon' => 'bi bi-heart-fill'],
            ['name' => 'Kesehatan',               'slug' => 'kesehatan',  'color' => '#27AE60', 'icon' => 'bi bi-hospital-fill'],
            ['name' => 'Pendidikan',              'slug' => 'pendidikan', 'color' => '#2980B9', 'icon' => 'bi bi-mortarboard-fill'],
            ['name' => 'Budaya Humanis',          'slug' => 'budaya',     'color' => '#8E44AD', 'icon' => 'bi bi-flower1'],
            ['name' => 'Pelestarian Lingkungan',  'slug' => 'lingkungan', 'color' => '#16A085', 'icon' => 'bi bi-tree-fill'],
            ['name' => 'Tanggap Bencana',         'slug' => 'bencana',    'color' => '#E67E22', 'icon' => 'bi bi-exclamation-triangle-fill'],
            ['name' => 'Benah Kampung',           'slug' => 'benah',      'color' => '#1A5276', 'icon' => 'bi bi-house-fill'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], array_merge($cat, ['is_active' => true]));
        }

        $this->call(SettingSeeder::class);
    }
}
