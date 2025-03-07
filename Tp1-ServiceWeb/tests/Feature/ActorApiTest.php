<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Film;
use App\Constants\HttpStatusCodes;

class ActorApiTest extends TestCase
{
    use RefreshDatabase;

    // Route 2
    public function test_can_get_all_actors_of_a_film()
    {
        // Arrange
        $this->seed();
        $film = Film::with('actors')->first();
        $actors = $film->actors;

        // Act
        $response = $this->getJson("/api/films/{$film->id}/actors");

        // Assert
        $response->assertStatus(HttpStatusCodes::OK);
        $response->assertJsonCount($actors->count(), 'data');

        foreach ($actors as $actor) {
            $response->assertJsonFragment([
                'id' => $actor->id,
                'name' => $actor->first_name . " " . $actor->last_name,
                'birth_date' => $actor->birthdate
            ]);
        }
    }

    public function test_returns_error_when_film_not_found()
    {
        // Act
        $response = $this->getJson('/api/films/99999999/actors');

        // Assert
        $response->assertStatus(HttpStatusCodes::NOT_FOUND);
        $response->assertJson([
            'error' => 'Film non trouvé',
        ]);
        
    }
}
