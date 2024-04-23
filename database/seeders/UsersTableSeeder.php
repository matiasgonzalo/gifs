<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
             'name' => 'Melanie',
             'email' => 'melanie@gmail.com',
        ]);

        User::factory()->create([
             'name' => 'Matias',
             'email' => 'matias@gmail.com',
        ]);

        User::factory(10)->create();
    }
}
