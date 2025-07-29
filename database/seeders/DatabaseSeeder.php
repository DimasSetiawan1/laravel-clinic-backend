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
        // User::factory(2)->create();

        User::create([
            'name' => "Mochamad Dimas Setiawan",
            'email' => "dimas@dimastech.my.id",
            'email_verified_at' => now(),
            'role' => 'admin',
            'password' => Hash::make('dimasdimas'),
            'remember_token' => Str::random(10),
        ]);

        // Pastikan sudah ada clinic dan specialist sebelum insert user doctor!
        $clinic = \App\Models\Clinic::first() ?? \App\Models\Clinic::create([
            'name' => 'Klinik Dimas',
            'address' => 'Jl. Pendidikan I no 4',
            'phone_number' => '02112345678',
            'email' => 'klinikdimas@example.com',
            'open_time' => '08:00',
            'close_time' => '17:00',
            'website' => env("APP_URL"),
            'description' => 'Klinik Dimas melayani kesehatan umum dan gigi.',
            'image' => null,
            'spesialis' => 'Dokter Umum, Dokter Gigi'
        ]);
        $specialistUmum = Specialist::firstOrCreate(['name' => 'Dokter Umum']);
        $specialistGigi = Specialist::firstOrCreate(['name' => 'Dokter Gigi']);

        // Doctor 1
        User::create([
            'name' => "Dr. Andi Wijaya",
            'email' => "andi1.dokter@example.com",
            'email_verified_at' => now(),
            'role' => 'doctor',
            'status' => 'active',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'google_id' => null,
            'ktp_number' => 1234567890,
            'phone_number' => '081234567890',
            'address' => 'Jl. Kesehatan No.1',
            'birth_date' => '1980-01-01',
            'gender' => 'Pria',
            'certification' => 'STR123456',
            'telemedicine_fee' => 50000,
            'chat_fee' => 25000,
            'start_time' => '08:00',
            'end_time' => '16:00',
            'clinic_id' => $clinic->id,
            'image' => null,
            'specialist_id' => $specialistUmum->id,
        ]);

        // Doctor 2
        User::create([
            'name' => "Dr. Siti Rahma",
            'email' => "siti.dokter@example.com",
            'email_verified_at' => now(),
            'role' => 'doctor',
            'status' => 'active',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'google_id' => null,
            'ktp_number' => 9876543210,
            'phone_number' => '081298765432',
            'address' => 'Jl. Sehat No.2',
            'birth_date' => '1985-05-05',
            'gender' => 'Wanita',
            'certification' => 'STR654321',
            'telemedicine_fee' => 60000,
            'chat_fee' => 30000,
            'start_time' => '09:00',
            'end_time' => '17:00',
            'clinic_id' => $clinic->id,
            'image' => null,
            'specialist_id' => $specialistGigi->id,
        ]);

        // Patient 1
        User::create([
            'name' => "Budi Santoso",
            'email' => "budi.pasien@example.com",
            'email_verified_at' => now(),
            'role' => 'patient',
            'status' => 'active',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'google_id' => null,
            'ktp_number' => 1122334455,
            'phone_number' => '081211223344',
            'address' => 'Jl. Pasien No.1',
            'birth_date' => '1990-02-02',
            'gender' => 'Pria',
            'certification' => null,
            'telemedicine_fee' => null,
            'chat_fee' => null,
            'start_time' => null,
            'end_time' => null,
            'clinic_id' => null,
            'image' => null,
            'specialist_id' => null,
        ]);

        // Patient 2
        User::create([
            'name' => "Dewi Lestari",
            'email' => "dewi.pasien@example.com",
            'email_verified_at' => now(),
            'role' => 'patient',
            'status' => 'active',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'google_id' => null,
            'ktp_number' => 5566778899,
            'phone_number' => '081255667788',
            'address' => 'Jl. Pasien No.2',
            'birth_date' => '1992-03-03',
            'gender' => 'Wanita',
            'certification' => null,
            'telemedicine_fee' => null,
            'chat_fee' => null,
            'start_time' => null,
            'end_time' => null,
            'clinic_id' => null,
            'image' => null,
            'specialist_id' => null,
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
