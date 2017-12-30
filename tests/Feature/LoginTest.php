<?php

namespace Tests\Feature;

use App\User;
use Tests\BaseTestCase;

class LoginTest extends BaseTestCase
{
    /** @var \App\User */
    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function login()
    {
        // invalid email format
        $this->post('/api/login', [
            'email' => 'foo',
            'password' => 'bar'
        ])->seeStatusCode(422)
            ->seeJsonStructure(['errors']);

        // wrong password
        $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'bar',
        ])->seeStatusCode(422)
            ->seeJsonStructure(['errors']);

        $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'secret',
        ])->seeStatusCode(200)
            ->seeJsonStructure(['token']);
    }

    /** @test */
    public function getUser()
    {
        $this->actingAs($this->user)
            ->get('/api/user')
            ->seeStatusCode(200)
            ->seeJsonStructure(['id', 'name', 'email']);
    }

    /** @test */
    public function logout()
    {
        $this->actingAs($this->user)
            ->post('/api/logout')
            ->seeStatusCode(200)
            ->seeJsonEquals(['success' => true]);

        $this->get('/api/user')
            ->assertResponseStatus(401);
    }
}
