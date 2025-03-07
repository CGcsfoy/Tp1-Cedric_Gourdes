<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Film;
use App\Models\Critic;
use App\Constants\HttpStatusCodes;

class CriticApiTest extends TestCase
{
    use RefreshDatabase;

    // Route 3
    public function test_can_get_all_critics_of_a_film()
    {
        // Arrange
        $this->seed();
        $film = Film::with('critics')->first();
        $critics = $film->critics;

        // Act
        $response = $this->getJson("/api/films/{$film->id}/critics");

        // Assert
        $response->assertStatus(HttpStatusCodes::OK);
        $response->assertJsonCount($critics->count(), 'data');

        foreach ($critics as $critic) {
            $response->assertJsonFragment([
                'id' => $critic->id,
                'film_id' => $critic->film_id,
                'user_id' => $critic->user_id,
                'score' => $critic->score,
                'comment' => $critic->comment,
                'created_at' => $critic->created_at,
            ]);
        }
    }

    public function test_returns_error_when_film_not_found()
    {
        // Act
        $response = $this->getJson('/api/films/999999/critics');

        // Assert
        $response->assertStatus(HttpStatusCodes::NOT_FOUND);
        $response->assertJson([
            'error' => 'Film non trouvé',
        ]);
    }

    // Route 6
    public function test_can_delete_a_critic()
    {
        // Arrange
        $this->seed();
        $critic = Critic::first();

        // Act
        $response = $this->deleteJson("/api/critics/{$critic->id}");

        // Assert
        $response->assertStatus(HttpStatusCodes::NO_CONTENT);
        $this->assertDatabaseMissing('critics', ['id' => $critic->id]);
    }

    public function test_returns_error_when_deleting_non_existent_critic()
    {
        // Act
        $response = $this->deleteJson('/api/critics/999999');

        // Assert
        $response->assertStatus(HttpStatusCodes::NOT_FOUND);
        $response->assertJson([
            'error' => 'Critique non trouvée',
        ]);
    }
}
