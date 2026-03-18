<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_should_delete_product_successfully(): void
    {
        $product = Product::factory()->create();

        $this->callRequest($product->id)
            ->assertNoContent();

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_should_return_404_when_trying_to_delete_product_that_does_not_exist(): void
    {
        $this->callRequest(-1)
            ->assertNotFound();
    }

    private function callRequest(int $id): TestResponse
    {
        return $this->deleteJson(route('products.destroy', $id));
    }
}
