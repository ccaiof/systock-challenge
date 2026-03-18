<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_successfully()
    {
        $payload = [
            "name" => 'Caio',
            "email" => 'caio@email.com',
            "cpf" => '12345678909',
            "password" => '123456',
            "password_confirmation" => '123456'
        ];

        $this->callRequest($payload)
            ->assertCreated()
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "name",
                    "email",
                ]
            ]);
    }

    public function test_should_not_create_user_with_existing_email()
    {
        $user = User::factory()->create();

        $payload = [
            "name" => 'Test user',
            "email" => $user->email,
            "cpf" => '12345678909',
            "password" => '123456'
        ];

        $this->callRequest($payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_should_not_create_user_with_existing_cpf()
    {
        $user = User::factory()->create();

        $payload = [
            "name" => 'Test user',
            "email" => 'test@email.com',
            "cpf" => $user->cpf,
            "password" => '123456'
        ];

        $this->callRequest($payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cpf']);
    }

    private function callRequest(array $payload): TestResponse
    {
        return $this->postJson(route('users.store'), $payload);
    }
}
