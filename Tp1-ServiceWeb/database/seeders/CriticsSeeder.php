<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Critic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            Critic::factory(30)->create(['user_id' => $user->id]);
        });
    }
}
