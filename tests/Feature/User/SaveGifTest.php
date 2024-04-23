<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SaveGifTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_authenticated_user_can_save_a_gif_with_user_gif_id_and_alias_params(): void
    {
        $mati = User::factory()->create(['name' => 'Mati', 'email' => 'mati@gmail.com']);
        Passport::actingAs(
            $mati,
            ['create-servers']
        );
        $response = $this->postJson(route('gifs.save', $mati), [
                'gif_id' => 'APqEbxBsVlkWSuFpth',
                'alias' => 'Mati',
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function an_authenticated_user_cannot_save_a_gif_without_gif_id_param(): void
    {
        $mati = User::factory()->create(['name' => 'Mati', 'email' => 'mati@gmail.com']);
        Passport::actingAs(
            $mati,
            ['create-servers']
        );
        $response = $this->postJson(route('gifs.save', $mati), [
            'alias' => 'Mati',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('gif_id');
    }

    /**
     * @test
     */
    public function an_authenticated_user_cannot_save_a_gif_without_alias_param(): void
    {
        $mati = User::factory()->create(['name' => 'Mati', 'email' => 'mati@gmail.com']);
        Passport::actingAs(
            $mati,
            ['create-servers']
        );
        $response = $this->postJson(route('gifs.save', $mati), [
            'gif_id' => 'APqEbxBsVlkWSuFpth',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('alias');
    }
}
