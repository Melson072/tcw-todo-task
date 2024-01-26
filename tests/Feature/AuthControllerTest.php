<?php

namespace Tests\Unit\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['accessToken', 'token_type']);
    }
    public function testUserCanLogout()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $token = $response->json('accessToken');
        $logoutResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->get('/api/auth/logout');
        $logoutResponse->assertStatus(200);
        $logoutResponse->assertJson(['message' => 'Successfully logged out']);
    }

    public function testUserCanNotLoginWithInvalid()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test02@example.com',
            'password' => 'passworda',
        ]);
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthorized']);
    }

}
