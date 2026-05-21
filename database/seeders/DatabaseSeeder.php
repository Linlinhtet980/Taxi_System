<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \Illuminate\Support\Facades\Hash::class;
        User::updateOrCreate([
            'email' => 'adminlin890@gmail.com',
        ], [
            'name' => 'AdminLin',
            'password' => \Illuminate\Support\Facades\Hash::make('adminlin890'),
            'role' => 'super_admin',
        ]);
    }
}
