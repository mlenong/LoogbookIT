<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Unit::insert([
            ['nama' => 'Gedung A', 'keterangan' => 'Poli Rawat Jalan'],
            ['nama' => 'Gedung B', 'keterangan' => 'IGD'],
            ['nama' => 'Gedung C', 'keterangan' => 'Farmasi'],
            ['nama' => 'Gedung D', 'keterangan' => 'Manajemen'],
            ['nama' => 'Ruang Server', 'keterangan' => 'Ruang Server Utama'],
        ]);
    }
}
