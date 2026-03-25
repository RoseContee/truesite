<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        Setting::insert([
            'key' => 'site_name',
            'value' => 'TrueSite',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Setting::insert([
            'key' => 'site_url',
            'value' => 'http://localhost:10002/',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Setting::insert([
            'key' => 'site_logo',
            'value' => 'assets/images/logo.png',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Setting::insert([
            'key' => 'favicon',
            'value' => 'assets/images/favicon.png',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
