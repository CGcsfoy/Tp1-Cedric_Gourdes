<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Database\QueryException;
use App\Constants\HttpStatusCodes;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    // Route 4
    public function test_can_create_a_user()
    {
        // Arrange
        $userData = [
            'login' => 'testuser',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ];

        // Act
        $response = $this->postJson('/api/users', $userData);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'login' => 'testuser',
            'email' => 'johndoe@example.com',
        ]);
        $this->assertDatabaseHas('users', [
            'login' => 'testuser',
            'email' => 'johndoe@example.com',
        ]);
    }

    // Route 5
    public function test_can_update_a_user()
    {
        // Arrange
        $this->seed();
        $user = User::findOrFail(1);
        $updateData = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'login' => 'janedoe',
            'email' => 'janedoe@example.com',
            'password' => 'newpassword123'
        ];

        // Act
        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->dump();

        // Assert
        $response->assertStatus(HttpStatusCodes::OK);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);
    }

    public function test_update_user_not_found()
    {
        // Act
        $response = $this->putJson('/api/users/9999', [
            'first_name' => 'Test',
            'login' => 'testuser',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
    
        // Assert
        $response->assertStatus(HttpStatusCodes::SERVER_ERROR);
        $response->assertJson(['error' => 'Erreur serveur']);
    }
    

    // Route 8 - Test de récupération de la langue préférée
    public function test_can_get_preferred_language()
    {
        // Arrange
        $this->seed();
        $user = User::first();

        // Act
        $response = $this->getJson("/api/users/{$user->id}/preferred-language");

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure(['preferred_language_id']);
    }

    public function test_preferred_language_user_not_found()
    {
        // Act
        $response = $this->getJson('/api/users/999999/preferred-language');

        // Assert
        $response->assertStatus(HttpStatusCodes::SERVER_ERROR);
        $response->assertJson(['error' => 'Erreur serveur']);
    }
}