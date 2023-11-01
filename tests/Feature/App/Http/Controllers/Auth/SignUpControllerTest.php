<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\SignUpController;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_sign_up_page_success(): void
    {
        $this->get(action([SignUpController::class, 'page']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up');
    }

    public function test_sign_up_success(): void
    {
        $request = SignUpFormRequest::factory()->create([
            'email' => 'test@mail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $request['email'],
        ]);

        $response = $this->post(
            action([SignUpController::class, 'handle']),
            $request
        );

        $response->assertValid();
        $this->assertDatabaseHas('users', [
            'email' => $request['email'],
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));
    }

    public function test_sign_up_failed_with_not_valid_data()
    {
        $request = SignUpFormRequest::factory()->create([
            'email' => 'test@test',
            'password' => '1234',
            'password_confirmation' => '1234',
        ]);

        $response = $this->post(
            action([SignUpController::class, 'handle']),
            $request
        );

        $response->assertInvalid(['email', 'password']);
        $this->assertDatabaseMissing('users', [
            'email' => $request['email'],
        ]);
    }

    public function test_sing_up_failed_with_exist_email()
    {
        $user = UserFactory::new()->create();

        $request = SignUpFormRequest::factory()->create([
            'email' => $user->email,
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ]);

        $response = $this->post(
            action([SignUpController::class, 'handle']),
            $request
        );

        $response->assertInvalid('email');
    }

    public function test_registered_event_fired()
    {
        Event::fake();

        $request = SignUpFormRequest::factory()->create([
            'email' => 'test@mail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response = $this->post(
            action([SignUpController::class, 'handle']),
            $request
        );

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);
    }

    public function test_notification_sent()
    {
        Notification::fake();

        $request = SignUpFormRequest::factory()->create([
            'email' => 'test@mail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $this->post(
            action([SignUpController::class, 'handle']),
            $request
        );

        $user = User::where('email', $request['email'])->first();

        Notification::assertSentTo($user, NewUserNotification::class);
    }
}
