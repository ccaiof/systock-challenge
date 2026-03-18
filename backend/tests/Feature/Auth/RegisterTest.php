<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
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

        $response = $this->callRequest($payload);

        $response
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "name",
                    "email",
                ],
                "token",
                "token_type",
            ])
            ->assertJsonPath('token_type', 'Bearer')
            ->assertCreated();

        $this->assertDatabaseHas('users', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'cpf' => $payload['cpf'],
        ]);

        $createdUser = User::where('email', $payload['email'])->first();

        $this->assertNotNull($createdUser);
        $this->assertTrue(Hash::check($password, $createdUser->password));

        $plainTextToken = $response->json('token');
        $this->assertNotEmpty($plainTextToken);
        $tokenValue = explode('|', $plainTextToken)[1] ?? $plainTextToken;

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $createdUser->id,
            'token' => hash('sha256', $tokenValue),
        ]);
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


        $response = $this->callRequest($payload);

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

        $response = $this->callRequest($payload);

        $response
            ->assertJsonValidationErrors(['cpf'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_not_register_when_password_confirmation_is_different()
    {
        $payload = [
            "name" => 'Test user',
            "email" => fake()->email(),
            "cpf" => fake()->cpf(),
            "password" => '123456',
            "password_confirmation" => '654321'
        ];

        $this->callRequest($payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_should_not_register_when_password_is_too_short()
    {
        $payload = [
            "name" => 'Test user',
            "email" => fake()->email(),
            "cpf" => fake()->cpf(),
            "password" => '12345',
            "password_confirmation" => '12345'
        ];

        $this->callRequest($payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    private function callRequest(array $payload): TestResponse
    {
        return $this->postJson(route('auth.register'), $payload);
    }
}
