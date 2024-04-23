<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SearchGifTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_user_can_search_a_gif_with_query_and_limit_params(): void
    {
        $mati = User::factory()->create(['name' => 'Mati', 'email' => 'mati@gmail.com']);
        Passport::actingAs(
            $mati,
            ['create-servers']
        );
        $response = $this->getJson(route('gifs.search', [
            'query' => 'Mati',
            'limit' => 1
        ]));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function an_user_cannot_search_a_gif_query_param_cannot_be_empty(): void
    {
        $mati = User::factory()->create(['name' => 'Mati', 'email' => 'mati@gmail.com']);
        Passport::actingAs(
            $mati,
            ['create-servers']
        );
        $response = $this->getJson(route('gifs.search', [
            'limit' => 1
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('query');
    }

    /**
     * @test
     */
    public function an_user_cannot_search_a_gif_query_limit_can_be_empty(): void
    {
        $mati = User::factory()->create(['name' => 'Mati', 'email' => 'mati@gmail.com']);
        Passport::actingAs(
            $mati,
            ['create-servers']
        );
        $response = $this->getJson(route('gifs.search', [
            'query' => 'Mati'
        ]));

        $response->assertStatus(200);
    }
}
