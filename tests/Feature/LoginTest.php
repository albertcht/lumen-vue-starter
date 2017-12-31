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
    public function testLogin()
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

        $this->assertGuest();

        $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'secret',
        ])->assertSuccessful()
            ->assertJsonStructure(['token']);

        $this->assertAuthenticated();
    }

    /** @test */
    public function testGetUser()
    {
        $this->actingAs($this->user)
            ->get('/api/user')
            ->assertSuccessful()
            ->assertJsonStructure(['id', 'name', 'email']);

        $this->assertAuthenticated();
    }

    /** @test */
    public function testLogout()
    {
        $this->actingAs($this->user)
            ->post('/api/logout')
            ->assertSuccessful()
            ->assertJson(['success' => true]);

        $this->assertGuest();

        $this->get('/api/user')
            ->assertStatus(401);
    }
}
