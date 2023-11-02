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

    public function test_page_success(): void
    {
        $this->get(action([SignInController::class, 'page']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    public function test_handle_success(): void
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

    public function test_handle_fail(): void
    {
        $request = SignInFormRequest::factory()->create([
            'email' => 'notfound@mail.com',
            'password' => str()->random(10),
        ]);

        $this->post(action([SignInController::class, 'handle']), $request)
            ->assertInvalid('email');

        $this->assertGuest();
    }

    public function test_logout_success(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@mail.com',
        ]);

        $this->actingAs($user)
            ->delete(action([SignInController::class, 'logOut']));

        $this->assertGuest();
    }

    public function test_logout_guest_middleware_fail()
    {
        $this->delete(action([SignInController::class, 'logOut']))
            ->assertRedirect(route('home'));
    }
}
