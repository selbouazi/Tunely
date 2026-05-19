<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'nombre' => 'Test',
            'apellido1' => 'User',
            'fecha_nacimiento' => now()->subYears(20)->format('Y-m-d'),
            'telefono' => '+34 612345678',
            'direccion' => 'Calle Test 123',
            'ciudad' => 'Barcelona',
            'provincia' => 'Barcelona',
            'codigo_postal' => '08001',
            'email' => 'test@example.com',
            'password' => 'Password1',
            'password_confirmation' => 'Password1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect();
    }
}
