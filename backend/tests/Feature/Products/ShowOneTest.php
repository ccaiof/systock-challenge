<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowOneTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_should_return_a_product_when_id_is_valid_and_exists()
    {
        $product = Product::factory()->create();

        $this->callRequest($product->id)
            ->assertOk()
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_should_return_404_when_id_is_not_found()
    {
        $this->callRequest(-1)
            ->assertNotFound();
    }

    private function callRequest(int $id): TestResponse
    {
        return $this->getJson(route('products.show', $id));
    }
}
