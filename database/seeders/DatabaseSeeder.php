<?php

namespace Database\Seeders;

use App\Models\Specialist;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(2)->create();

        User::create([
            'name' => "M Dimas Setiawan",
            'email' => "dimas@dimas.com",
            'email_verified_at' => now(),
            'role' => 'admin',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Specialist::create([
            'name' => 'Dokter umum',
        ]);
        Specialist::create([
            'name' => 'Dokter Gigi',
        ]);
        Specialist::create([
            'name' => 'Dokter Kandungan',
        ]);
    }
}
