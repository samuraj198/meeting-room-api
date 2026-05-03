<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        $data = [
            'name' => 'daniil',
            'email' => 'daniil@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        $response = $this->post('/api/register', $data);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'role',
                    'email'
                ],
                'token'
            ]
        ])->assertStatus(201);
    }

    public function test_login_user()
    {
        $user = User::factory()->create([
            'password' => '12345678'
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678'
        ]);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'role',
                    'email'
                ],
                'token'
            ]
        ])->assertStatus(200);
    }

    public function test_logout_user()
    {
        $user = User::factory()->create();

        $token = $user->createToken('token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->post('/api/logout');

        $response->assertJsonStructure([
            'success',
            'message'
        ])->assertStatus(200);
    }
}
