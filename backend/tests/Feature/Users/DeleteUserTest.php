<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_should_delete_user_successfully(): void
    {
        $user = User::factory()->create();

        $this->callRequest($user->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_should_return_404_when_trying_to_delete_user_that_does_not_exist(): void
    {
        $idNotFound = -1;

        $this->callRequest($idNotFound)
            ->assertNotFound()
            ->assertJsonFragment([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => __('errors.user.not_found', ['id' => $idNotFound]),
            ]);
    }

    private function callRequest(int $id): TestResponse
    {
        return $this->deleteJson(route('users.destroy', $id));
    }
}
