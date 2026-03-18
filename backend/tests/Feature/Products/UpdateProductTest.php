<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_should_update_product_successfully()
    {
        $product = Product::factory()->create([
            'nome' => 'Old Product',
            'preco' => 10,
        ]);

        $payload = [
            'nome' => 'New Product',
            'preco' => 20.50,
            'descricao' => 'Descricao atualizada',
        ];

        $this->callRequest($product->id, $payload)
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.nome', $payload['nome'])
            ->assertJsonPath('data.preco', $payload['preco'])
            ->assertJsonPath('data.descricao', $payload['descricao']);
    }

    public function test_should_return_404_when_trying_to_update_product_that_does_not_exist()
    {
        $payload = [
            'nome' => 'Any Name',
        ];

        $this->callRequest(-1, $payload)
            ->assertNotFound();
    }

    public function test_should_not_update_product_when_preco_is_not_positive()
    {
        $product = Product::factory()->create();

        $payload = [
            'preco' => 0,
        ];

        $this->callRequest($product->id, $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['preco']);
    }

    private function callRequest(int $id, array $payload): TestResponse
    {
        return $this->putJson(route('products.update', $id), $payload);
    }
}
