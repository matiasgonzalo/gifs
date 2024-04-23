<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SearchGifTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        /** @var User user */
        $this->user = User::factory()->create(['name' => 'Mati', 'email' => 'mati@gmail.com']);
        Passport::actingAs(
            $this->user,
            ['create-servers']
        );
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_search_a_gif_with_query_and_limit_params(): void
    {
        $response = $this->getJson(route('gifs.search', [
            'query' => 'Mati',
            'limit' => 1
        ]));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function an_authenticated_user_cannot_search_a_gif_because_query_param_cannot_be_empty(): void
    {
        $response = $this->getJson(route('gifs.search', [
            'limit' => 1
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('query');
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_search_a_gif_without_limit_param(): void
    {
        $response = $this->getJson(route('gifs.search', [
            'query' => 'Mati'
        ]));

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function an_unauthenticated_user_cannot_search_a_gif(): void
    {
        $this->refreshApplication();
        $response = $this->getJson(route('gifs.search', [
            'query' => 'Mati'
        ]));

        $response->assertStatus(401);
    }
}
