<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Film;
use App\Constants\HttpStatusCodes;

define("DEFAULT_PAGINATION", 20);

class FilmApiTest extends TestCase
{
    use RefreshDatabase;

    // Route 1
    public function test_can_get_all_films()
    {
        // Arrange
        $this->seed();
        $movieArray = Film::paginate(DEFAULT_PAGINATION);
        $response = $this->getJson('/api/films'); 
    
        // Act/Assert
        $response->assertJsonCount($movieArray->count(), 'data');
    
        foreach ($movieArray as $movie) {
            $response->assertJsonFragment([
                'id' => $movie->id,
                'title' => $movie->title,
                'description' => $movie->description,
                'release_year' => $movie->release_year,
                'rating' => $movie->rating,
                'length' => $movie->length

            ]);
        }
    
        $response->assertStatus(HttpStatusCodes::OK);
    }

    // Route 7
    public function test_can_get_average_score()
    {
        // Arrange
        $this->seed();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
        $film = Film::findOrFail(1);
        $expectedAverage = $film->critics()->avg('score');

        // Act
        $response = $this->getJson("/api/films/{$film->id}/average-score");

        // Assert
        $response->assertStatus(HttpStatusCodes::OK);
        $response->assertJson([
            'average_score' => $expectedAverage
        ]);
    }

    // Route 9
    public function test_can_search_films()
    {
        // Arrange
        $this->seed();
        $matchingFilm = Film::findOrFail(1);
        $nonMatchingFilm = Film::findOrFail(2);

        // Act
        $response = $this->getJson('/api/films/search?keyword=ACADEMY DINOSAUR&rating=PG&minLength=85&maxLength=87');

        // Assert
        $response->assertStatus(HttpStatusCodes::OK);
        $response->assertJsonFragment([
            'id' => $matchingFilm->id,
            'title' => 'ACADEMY DINOSAUR',
            'rating' => 'PG',
            'length' => 86
        ]);
    }
    
}