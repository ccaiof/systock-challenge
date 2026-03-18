<?php

namespace Tests\Feature\Products;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_create_product_successfully()
    {
        $user = User::factory()->create();

        $payload = [
            'user_id' => $user->id,
            'nome' => 'Mouse Gamer',
            'preco' => 199.90,
            'descricao' => 'Mouse sem fio com RGB',
        ];

        $this->callRequest($payload)
            ->assertCreated()
            ->assertJsonPath('data.user_id', $user->id)
            ->assertJsonPath('data.nome', $payload['nome'])
            ->assertJsonPath('data.preco', $payload['preco'])
            ->assertJsonPath('data.descricao', $payload['descricao']);
    }

    public function test_should_not_create_product_with_invalid_user()
    {
        $payload = [
            'user_id' => -1,
            'nome' => 'Teclado',
            'preco' => 99.90,
        ];

        $this->callRequest($payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['user_id']);
    }

    public function test_should_not_create_product_when_name_is_missing()
    {
        $user = User::factory()->create();

        $payload = [
            'user_id' => $user->id,
            'preco' => 99.90,
        ];

        $this->callRequest($payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nome']);
    }

    public function test_should_not_create_product_when_preco_is_not_positive()
    {
        $user = User::factory()->create();

        $payload = [
            'user_id' => $user->id,
            'nome' => 'Produto teste',
            'preco' => 0,
        ];

        $this->callRequest($payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['preco']);
    }

    private function callRequest(array $payload): TestResponse
    {
        return $this->postJson(route('products.store'), $payload);
    }
}
