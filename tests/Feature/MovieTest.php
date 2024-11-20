<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use App\Models\User;

// TODO:
// Test para verificar que no se pueda crear una Movie vacia
// Test para verificar que solo admin pueda crear una Movie
class MovieTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function withUser(): self
    {
        $user = new User();
        $user->user_id = 1;
        $user->isAdmin = true;

        return $this->actingAs($user);
    }

    public function test_cannot_create_a_movie_without_permission()
    {
        $user = new User();
        $user->user_id = 2;
        $user->isAdmin = false;

        $postData = [
            'country_id' => 2,
            'classification_id' => 1,
            'title' => 'Corazón de Dragón',
            'synopsis' => 'La aventura de un caballero con el último dragón.',
            'release_date' => '1998-02-16',
            'price' => 19.99,
        ];

        $response = $this->actingAs($user)->postJson('/api/movies', $postData);

        $response
            ->assertStatus(403);
    }

    public function test_can_determine_if_admin_can_get_all_movies(): void
    {
        $response = $this->withUser()->getJson('/api/movies');

        $response
            ->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'movie_id',
                        'country_id',
                        'classification_id',
                        'title',
                        'synopsis',
                        'price',
                        'release_date',
                        'cover',
                        'cover_description',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ],
                ],
            ])
            ->assertJsonPath('status', 0);;
    }

    public function test_can_determine_if_admin_can_get_a_movie_by_id(): void
    {
        $id = 1;
        $response = $this->withUser()->getJson('/api/movies/' . $id);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json
                ->where('status', 0)
                ->where('data.movie_id', $id)
                ->where('data.title', 'El Señor de los Anillos: La Comunidad del Anillo')
                ->whereAllType([
                    'data.movie_id' => 'integer',
                    'data.country_id' => 'integer',
                    'data.classification_id' => 'integer',
                    'data.title' => 'string',
                    'data.price' => 'integer|double',
                    'data.release_date' => 'string',
                    'data.synopsis' => 'string',
                    'data.cover' => 'string|null',
                    'data.cover_description' => 'string|null',
                    'data.created_at' => 'string',
                    'data.updated_at' => 'string',
                    'data.deleted_at' => 'string|null',
                ])
            );
    }

    public function test_can_determine_if_admin_can_create_a_movie(): void
    {
        $postData = [
            'country_id' => 2,
            'classification_id' => 1,
            'title' => 'Corazón de Dragón',
            'synopsis' => 'La aventura de un caballero con el último dragón.',
            'release_date' => '1998-02-16',
            'price' => 19.99,
        ];

        $response = $this->withUser()->postJson('/api/movies', $postData);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where('status', 0)
                    ->where('data.movie_id', 5)
            );
    }

    public function test_can_determine_if_admin_can_update_a_movie(): void
    {
        $id = 1;
        $postData = [
            'title' => 'Corazón de Dragón',
        ];

        $response = $this->withUser()->putJson('/api/movies/' . $id, $postData);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where('status', 0)
                    ->where('data.movie_id', 1)
                    ->where('data.title', $postData['title'])
            );
    }

    public function test_can_determine_if_admin_can_delete_a_movie(): void
    {
        $id = 1;
        $response = $this->withUser()->deleteJson('/api/movies/' . $id);

        $response
            ->assertStatus(200);
    }
}
