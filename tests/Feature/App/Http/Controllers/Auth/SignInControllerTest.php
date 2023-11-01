<?php

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\SignInController;
use App\Http\Requests\SignInFormRequest;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignInControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_success(): void
    {
        $this->get(action([SignInController::class, 'page']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    public function test_sign_in_success(): void
    {
        $password = '123456789';

        $user = UserFactory::new()->create([
            'email' => 'test@mail.com',
            'password' => bcrypt($password),
        ]);

        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->post(action([SignInController::class, 'handle']), $request);

        $this->assertAuthenticatedAs($user);
        $response->assertValid()
            ->assertRedirect(route('home'));
    }

    public function test_sign_in_failed_with_incorrect_password()
    {
        $incorrectPassword = '987654321';

        $user = UserFactory::new()->create([
            'email' => 'test@mail.com',
            'password' => bcrypt('123456789'),
        ]);

        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $incorrectPassword,
        ]);

        $response = $this->post(action([SignInController::class, 'handle']), $request);

        $response->assertInvalid('email');
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = UserFactory::new()->create([
            'email' => 'test@mail.com',
            'password' => bcrypt('123456789'),
        ]);

        $response = $this->actingAs($user)->delete(action([SignInController::class, 'logOut']));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }
}
