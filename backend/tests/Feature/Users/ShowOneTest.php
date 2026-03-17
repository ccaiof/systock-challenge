<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ShowOneTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_return_a_user_when_id_is_valid_and_exists()
    {
        $user = User::factory()->create();

        $this->callRequest($user->id)
            ->assertOk()
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_should_return_404_when_id_is_not_found()
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
        return $this->getJson(route('users.show', $id));
    }
}
