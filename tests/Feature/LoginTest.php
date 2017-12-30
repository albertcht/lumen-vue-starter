<?php

namespace Tests\Feature;

use App\User;
use Tests\BaseTestCase;
use AlbertCht\Lumen\Testing\Concerns\RefreshDatabase;

class LoginTest extends BaseTestCase
{
    use RefreshDatabase;

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
        ])->assertStatus(422)
            ->assertJsonStructure(['errors']);

        // wrong password
        $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'bar',
        ])->assertStatus(422)
            ->assertJsonStructure(['errors']);

        $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'secret',
        ])->assertSuccessful()
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function getUser()
    {
        $this->actingAs($this->user)
            ->get('/api/user')
            ->assertSuccessful()
            ->assertJsonStructure(['id', 'name', 'email']);
    }

    /** @test */
    public function logout()
    {
        $this->actingAs($this->user)
            ->post('/api/logout')
            ->assertSuccessful()
            ->assertJson(['success' => true]);

        $this->get('/api/user')
            ->assertStatus(401);
    }
}
