<?php

namespace Tests\Feature;

use App\User;
use Tests\BaseTestCase;
use AlbertCht\Lumen\Testing\Concerns\RefreshDatabase;

class RegisterTest extends BaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function testRegister()
    {
        // invalid data
        $test = $this->post('/api/register', $data = [
            'name' => 'Test User',
            'email' => 'test',
            'password' => 'secret',
            'password_confirmation' => 'secret2'
        ])->assertStatus(422);

        // success
        $response = $this->post('/api/register', $data = [
            'name' => 'Test User',
            'email' => 'test@test.app',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ])->assertSuccessful()
            ->assertJsonStructure(['id', 'name', 'email'])
            ->assertJson([
                'name' => $data['name'],
                'email' => $data['email']
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $response->json()['id'],
            'name' => $data['name'],
            'email' => $data['email']
        ]);
    }
}
