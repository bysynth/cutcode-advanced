<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_reset_page_success(): void
    {
        Notification::fake();

        $user = UserFactory::new()->create([
            'email' => 'testing@mail.com'
        ]);

        $this->post(action([ForgotPasswordController::class, 'handle']), [
            'email' => $user->email
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) {
            $response = $this->get(action([ResetPasswordController::class, 'page'], $notification->token));

            $response->assertOk()
                ->assertSee('Восстановление пароля')
                ->assertViewIs('auth.reset-password');

            return true;
        });
    }

    public function test_password_reset_process_success()
    {
        Notification::fake();

        $user = UserFactory::new()->create([
            'email' => 'testing@mail.com'
        ]);

        $this->post(action([ForgotPasswordController::class, 'handle']), [
            'email' => $user->email
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $response = $this->post(action([ResetPasswordController::class, 'handle']), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => '123456789',
                'password_confirmation' => '123456789',
            ]);

            $response->assertValid();
            $response->assertRedirectToRoute('login');
            $response->assertSessionHas('flash_message', __(Password::PASSWORD_RESET));

            return true;
        });
    }

    public function test_password_reset_process_failed_with_validation_errors()
    {
        Notification::fake();

        $user = UserFactory::new()->create([
            'email' => 'testing@mail.com'
        ]);

        $this->post(action([ForgotPasswordController::class, 'handle']), [
            'email' => $user->email
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) {
            $response = $this->post(action([ResetPasswordController::class, 'handle']), [
                'token' => $notification->token,
                'email' => 'failed@mail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertInvalid('email');

            return true;
        });
    }
}
