<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_login_successfully_and_return_token()
    {
        $password = '123456';
        $user = User::factory()->create([
            'password' => $password,
        ]);

        $response = $this->postJson(route('api.auth.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
                'token',
                'token_type',
            ])
            ->assertJsonPath('status', Response::HTTP_OK)
            ->assertJsonPath('token_type', 'Bearer');
    }

    public function test_should_return_unauthorized_when_credentials_are_invalid()
    {
        $user = User::factory()->create([
            'password' => '123456',
        ]);

        $this->postJson(route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonPath('status', Response::HTTP_UNAUTHORIZED);
    }

    public function test_should_logout_and_revoke_user_tokens()
    {
        $password = '123456';
        $user = User::factory()->create([
            'password' => $password,
        ]);

        $loginResponse = $this->postJson(route('api.auth.login'), [
            'email' => $user->email,
            'password' => $password,
        ])->assertOk();

        $plainTextToken = $loginResponse->json('token');
        $this->assertNotEmpty($plainTextToken);

        $this->withToken($plainTextToken)
            ->postJson(route('api.auth.logout'))
            ->assertOk();

        $tokenValue = explode('|', $plainTextToken)[1] ?? $plainTextToken;

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
            'token' => hash('sha256', $tokenValue),
        ]);
    }
}
