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
            'email' => 'taufiqjamil@sekolah.smp.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Candra Pardiana',
            'email' => 'candrapardiana@sekolah.smp.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Yernawati',
            'email' => 'yernawati@sekolah.smp.com',
            'password' => Hash::make('admin123'),
            'role' => 'petugas'
        ]);
        User::create([
            'name' => 'Tiara Azizah',
            'email' => 'tiaraazizah@sekolah.smp.com',
            'password' => Hash::make('admin123'),
            'role' => 'petugas'
        ]);
        User::create([
            'name' => 'Muhammad Azizan',
            'email' => 'muhammadazizan@sekolah.smp.com',
            'password' => Hash::make('admin123'),
            'role' => 'petugas'
        ]);
        User::create([
            'name' => 'Risma Fauzia',
            'email' => 'rismafauzia@sekolah.smp.com',
            'password' => Hash::make('admin123'),
            'role' => 'petugas'
        ]);
    }
}
