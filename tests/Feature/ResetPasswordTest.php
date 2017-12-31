<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use AlbertCht\Lumen\Testing\Concerns\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\BaseTestCase;

class ResetPasswordTest extends BaseTestCase
{
    use RefreshDatabase;

    public function testSendResetLinkEmail()
    {
        Mail::fake();
        Notification::fake();

        $user = factory(User::class)->create();
        $response = $this->post("/api/password/email", $data = [
            'email' => $user->email
        ]);

        $response->assertSuccessful()
            ->assertJsonStructure(['status']);

        Notification::assertSentTo(
            $user,
            ResetPasswordNotification::class
        );

        $this->assertDatabaseHas('password_resets', [
            'email' => $data['email']
        ]);
    }

    public function testResetPassword()
    {
        $user = factory(User::class)->create();
        $token = Password::broker()->getRepository()->create($user);
        $password = 'sasaya_seafood';

        $response = $this->post("/api/password/reset", $data = [
            'email' => $user->email,
            'token' => $token,
            'password' => $password,
            'password_confirmation' => $password
        ]);

        $response->assertSuccessful()
            ->assertJsonStructure(['status']);

        $this->assertDatabaseMissing('password_resets', [
            'email' => $data['email']
        ]);
    }
}
