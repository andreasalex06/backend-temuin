<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_nim(): void
    {
        $response = $this->postJson('/api/register', [
            'nama' => 'Andre',
            'nim' => '2301999',
            'password' => 'secret123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('user.nim', '2301999')
            ->assertJsonStructure(['message', 'token', 'user']);
    }

    public function test_user_can_login_and_fetch_profile(): void
    {
        $this->seed();

        $loginResponse = $this->postJson('/api/login', [
            'nim' => '2301001',
            'password' => 'temuin123',
        ]);

        $token = $loginResponse->json('token');

        $loginResponse
            ->assertOk()
            ->assertJsonPath('user.nama', 'Admin TemuIN');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('user.nim', '2301001');
    }
}
