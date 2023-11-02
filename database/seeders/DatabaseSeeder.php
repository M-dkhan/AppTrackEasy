<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $sampleData = [
            'job_title' => 'Sample Job Title',
            'company_name' => 'Sample Company Name',
            'application_date' => '2023-10-01',
            'application_deadline' => '2023-11-15',
            'status' => 'Applied',
            'contact_information' => 'sample@example.com',
            'notes_or_comments' => 'Sample notes or comments',
            'user_id' => 1, // Set the user_id to an existing user's ID
        ];

        for ($i = 0; $i < 50; $i++) {
            DB::table('jobs')->insert($sampleData);
        }
    }
}
