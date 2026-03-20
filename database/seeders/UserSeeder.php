<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Taufiq Jamil Hanafi',
            'email' => 'admin1@sekolah.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Candra Pardiana',
            'email' => 'admin2@sekolah.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Yernawati',
            'email' => 'petugas1@sekolah.com',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas'
        ]);
        User::create([
            'name' => 'Tiara Azizah',
            'email' => 'petugas2@sekolah.com',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas'
        ]);
        User::create([
            'name' => 'Muhammad Azizan',
            'email' => 'petugas3@sekolah.com',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas'
        ]);
    }
}
