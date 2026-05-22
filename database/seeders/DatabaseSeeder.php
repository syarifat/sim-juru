<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TahunAjaranSeeder::class,
            KelasSeeder::class,
            MataPelajaranSeeder::class,
            MasterJamPelajaranSeeder::class,
            JadwalSeeder::class,
            JurnalGuruSeeder::class,
        ]);
    }
}