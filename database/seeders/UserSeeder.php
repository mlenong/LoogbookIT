<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::firstOrCreate(
            ['nik' => '101117'],
            [
                'name' => 'Anang',
                'email' => 'anang@it.com',
                'password' => \Illuminate\Support\Facades\Hash::make('12345'),
            ]
        );
    }
}
