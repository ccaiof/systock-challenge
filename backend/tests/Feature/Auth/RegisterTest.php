<?php

namespace Tests\Feature\Auth;

use App\DTOs\User\CreateUserRequestDTO;
use App\DTOs\User\CreateUserResponseDTO;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_do_register_when_user_successfully()
    {
        $name = fake()->name();
        $email = fake()->email();
        $user = new CreateUserRequestDTO(
            $name,
            $email,
            fake()->cpf(),
            fake()->password(8),
        );
        $expectedResponse = new CreateUserResponseDTO(
            1,
            $name,
            $email,
        );

        $response = $this->postJson(route('auth.register'), $user->toArray());

        $response
            ->assertExactJson($expectedResponse->toArray())
            ->assertCreated();
    }

    public function test_should_not_register_when_user_with_existing_email()
    {
        app()->setLocale('pt_BR');

        $user = User::factory()->create();

        $userError = new CreateUserRequestDTO(
            $user->name,
            $user->email,
            fake()->cpf(),
            fake()->password(8),
        );

        $response = $this->postJson(route('auth.register'), $userError->toArray());

        $response
            ->assertJsonValidationErrors(['email'])
            ->assertJsonFragment([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => __('validation.failed')
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_not_register_when_user_with_existing_cpf()
    {
        $user = User::factory()->create();

        $userError = new CreateUserRequestDTO(
            $user->name,
            fake()->email(),
            $user->cpf,
            fake()->password(8),
        );

        $response = $this->postJson(route('auth.register'), $userError->toArray());

        $response
            ->assertJsonValidationErrors(['cpf'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
