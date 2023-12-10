<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testUserRegistration(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test1@mail.ua',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(201)->assertJsonFragment([
            'status' => 'success',
        ]);
    }

    public function testUserLogin(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email'    => 'test1@mail.ua',
            'password' => '12345678',
        ]);
        $response = $this->post( '/api/login', [
            'email'    => 'test1@mail.ua',
            'password' => '12345678',
        ]);

        $response->assertStatus(200)->assertJsonFragment([
            'status' => 'success',
        ]);
    }
}
