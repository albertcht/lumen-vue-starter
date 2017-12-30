<?php

namespace Tests\Feature;

use App\User;
use Tests\BaseTestCase;

class RegisterTest extends BaseTestCase
{
    /** @test */
    public function register()
    {
        // invalid data
        $test = $this->post('/api/register', $data = [
            'name' => 'Test User',
            'email' => 'test',
            'password' => 'secret',
            'password_confirmation' => 'secret2'
        ])->seeStatusCode(422);

        // success
        $response = $this->post('/api/register', $data = [
            'name' => 'Test User',
            'email' => 'test@test.app',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ])->seeStatusCode(200)
            ->seeJsonStructure(['id', 'name', 'email'])
            ->seeJson([
                'name' => $data['name'],
                'email' => $data['email']
            ])->toArray();

        $this->seeInDatabase('users', [
            'id' => $response['id'],
            'name' => $response['name'],
            'email' => $response['email']
        ]);
    }
}
