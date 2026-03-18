<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_do_register_when_user_successfully()
    {
        $password = fake()->password(6);
        $payload = [
            "name" => fake()->name(),
            "email" => fake()->email(),
            "cpf" => fake()->cpf(),
            "password" => $password,
            "password_confirmation" => $password
        ];

        $response = $this->postJson(route('auth.register'), $payload);

        $response
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "name",
                    "email",
                ]
            ])
            ->assertCreated();
    }

    public function test_should_not_register_when_user_with_existing_email()
    {
        app()->setLocale('pt_BR');

        $user = User::factory()->create();
        $password = fake()->password(8);

        $payload = [
            "name" => 'Test user',
            "email" => $user->email,
            "cpf" => fake()->cpf(),
            "password" => $password,
            "password_confirmation" => $password
        ];


        $response = $this->postJson(route('auth.register'), $payload);

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
        $password = fake()->password(8);
        $payload = [
            "name" => 'Test user',
            "email" => fake()->email(),
            "cpf" => $user->cpf,
            "password" => $password,
            "password_confirmation" => $password
        ];

        $response = $this->postJson(route('auth.register'), $payload);

        $response
            ->assertJsonValidationErrors(['cpf'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
