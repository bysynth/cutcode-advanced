<?php

namespace Tests\Feature\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterNewUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_user_created()
    {
        $this->assertDatabaseMissing('users', [
            'email' => 'testing@mail.com'
        ]);

        $action = app(RegisterNewUserContract::class);

        $action(NewUserDTO::make('Test', 'testing@mail.com', '1234567890'));

        $this->assertDatabaseHas('users', [
            'email' => 'testing@mail.com'
        ]);
    }
}
