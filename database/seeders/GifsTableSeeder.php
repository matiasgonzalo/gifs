<?php

namespace Database\Seeders;

use App\Models\Gif;
use App\Models\User;
use Illuminate\Database\Seeder;

class GifsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            /** @var Gif $gif */
            $gif = Gif::factory()->create();
            $user->gifs()->sync($gif->id);
        }
    }
}
