<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Certificate;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'A.Cassie@Yes.My',
        ]);

        User::factory()->create([
            'name' => 'Super Tester',
            'email' => 'A.Cassie@portaldev.ytlcomms.my',
        ]);

        Template::factory()
            ->count(2)
            ->has(Certificate::factory()->count(5))
            ->create();
    }
}
