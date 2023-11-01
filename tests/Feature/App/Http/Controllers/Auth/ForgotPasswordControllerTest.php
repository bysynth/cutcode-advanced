<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_page_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }

    public function test_reset_password_notification_sent()
    {
        Notification::fake();

        $user = UserFactory::new()->create([
            'email' => 'testing@mail.com'
        ]);

        $response = $this->post(action([ForgotPasswordController::class, 'handle']), [
            'email' => $user->email
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
        $response->assertSessionHas('flash_message', __(Password::RESET_LINK_SENT));
    }

    public function test_cannot_send_reset_password_notification_to_not_exists_user()
    {
        Notification::fake();

        $email = 'testing@mail.com';

        $response = $this->post(action([ForgotPasswordController::class, 'handle']), [
            'email' => $email
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $email,
        ]);
        $response->assertInvalid('email');
        Notification::assertNothingSent();
    }
}
