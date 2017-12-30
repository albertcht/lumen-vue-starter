<?php

namespace Tests\Feature;

use App\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery as m;
use Tests\BaseTestCase;
use AlbertCht\Lumen\Testing\Concerns\RefreshDatabase;

class OAuthTest extends BaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function redirectToProvider()
    {
        $this->mockSocialite('github');

        $this->get('/oauth/github')
            ->assertRedirect('https://url-to-provider');
    }

    /** @test */
    public function createUserAndRedirectHomeWithToken()
    {
        $this->mockSocialite('github', [
            'id' => '123',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'token' => 'access-token',
            'refreshToken' => 'refresh-token',
        ]);

        $this->get('/oauth/github/callback')
            ->assertRedirect('/home')
            ->assertCookie('token');

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('oauth_providers', [
            'user_id' => User::first()->id,
            'provider' => 'github',
            'provider_user_id' => '123',
            'access_token' => 'access-token',
            'refresh_token' => 'refresh-token',
        ]);
    }

    /** @test */
    public function updateUserAndRedirectHomeWithToken()
    {
        $user = factory(User::class)->create(['email' => 'test@example.com']);
        $user->oauthProviders()->create([
            'provider' => 'github',
            'provider_user_id' => '123',
        ]);

        $this->mockSocialite('github', [
            'id' => '123',
            'email' => 'test@example.com',
            'token' => 'updated-access-token',
            'refreshToken' => 'updated-refresh-token',
        ]);

        $this->get('/oauth/github/callback')
            ->assertRedirect('/home')
            ->assertCookie('token');

        $this->assertDatabaseHas('oauth_providers', [
            'user_id' => $user->id,
            'access_token' => 'updated-access-token',
            'refresh_token' => 'updated-refresh-token',
        ]);
    }

    /** @test */
    public function canNotCreateUserIfEmailIsTaken()
    {
        factory(User::class)->create(['email' => 'test@example.com']);

        $this->mockSocialite('github', ['email' => 'test@example.com']);

        $this->get('/oauth/github/callback')
            ->assertRedirect('/?error=email_taken');
    }

    protected function mockSocialite($driver, $user = null)
    {
        $mock = Socialite::shouldReceive('stateless')
            ->andReturn(m::self())
            ->shouldReceive('driver')
            ->with($driver)
            ->andReturn(m::self());

        if ($user) {
            $mock->shouldReceive('user')
                ->andReturn((new SocialiteUser)->setRaw($user)->map($user));
        } else {
            $mock->shouldReceive('redirect')
                ->andReturn(redirect('https://url-to-provider'));
        }
    }
}
