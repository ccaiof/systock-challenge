<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListByUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_should_list_only_products_from_given_user()
    {
        $targetUser = User::factory()->create();
        Product::factory()->count(3)->create(['user_id' => $targetUser->id]);

        $anotherUser = User::factory()->create();
        Product::factory()->count(2)->create(['user_id' => $anotherUser->id]);

        $response = $this->callRequest($targetUser->id)
            ->assertOk()
            ->assertJsonPath('meta.total', 3)
            ->assertJsonCount(3, 'data');

        $this->assertTrue(
            $response->collect('data')->every(fn (array $product): bool => $product['user_id'] === $targetUser->id)
        );
    }

    public function test_should_return_empty_list_when_user_has_no_products()
    {
        $userWithoutProducts = User::factory()->create();

        $this->callRequest($userWithoutProducts->id)
            ->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0);
    }

    private function callRequest(int $userId): TestResponse
    {
        return $this->getJson(route('users.products.index', $userId));
    }
}
