<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GetByIdGifTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_user_can_get_by_id_a_gif_with_id_param(): void
    {
        $mati = User::factory()->create(['name' => 'Mati', 'email' => 'mati@gmail.com']);
        Passport::actingAs(
            $mati,
            ['create-servers']
        );

        $response = $this->getJson(route('gifs.getById', [
            'id' => 'APqEbxBsVlkWSuFpth',
        ]));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function an_user_cannot_get_by_id_a_gif_without_id_param(): void
    {
        $mati = User::factory()->create(['name' => 'Mati', 'email' => 'mati@gmail.com']);
        Passport::actingAs(
            $mati,
            ['create-servers']
        );

        $response = $this->getJson(route('gifs.getById'));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('id');
    }
}