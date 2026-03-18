<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_should_update_user_successfully()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@email.com',
        ]);

        $payload = [
            'name' => 'New Name',
            'email' => 'new@email.com',
        ];

        $this->callRequest($user->id, $payload)
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.email', $payload['email']);
    }

    public function test_should_update_only_name_when_email_is_not_sent()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@email.com',
        ]);

        $payload = [
            'name' => 'Updated Name',
        ];

        $this->callRequest($user->id, $payload)
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_should_return_404_when_trying_to_update_user_that_does_not_exist()
    {
        $idNotFound = -1;
        $payload = [
            'name' => 'Any Name',
            'email' => 'any@email.com',
        ];

        $this->callRequest($idNotFound, $payload)
            ->assertNotFound()
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => __('errors.user.not_found', ['id' => $idNotFound]),
            ]);
    }

    public function test_should_not_update_user_with_existing_email()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $payload = [
            'name' => 'Updated Name',
            'email' => $anotherUser->email,
        ];

        $this->callRequest($user->id, $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_should_not_update_user_when_name_is_missing()
    {
        $user = User::factory()->create();

        $payload = [
            'email' => 'updated@email.com',
        ];

        $this->callRequest($user->id, $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    private function callRequest(int $id, array $payload): TestResponse
    {
        return $this->putJson(route('users.update', $id), $payload);
    }
}
