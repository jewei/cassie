<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Certificate;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::disk('local')->makeDirectory('backgrounds');
        Storage::disk('public')->makeDirectory('thumbnails');

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
        ]);

        Template::factory()
            ->count(2)
            ->has(Certificate::factory()->count(5))
            ->create();
    }
}
