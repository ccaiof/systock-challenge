<?php

namespace Tests\Feature\Products;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateByUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_should_create_product_using_user_from_route(): void
    {
        $user = User::factory()->create();

        $payload = [
            'nome' => 'Notebook Pro',
            'preco' => 7999.90,
            'descricao' => 'Produto criado pela rota aninhada',
        ];

        $this->callRequest($user->id, $payload)
            ->assertCreated()
            ->assertJsonPath('data.user_id', $user->id)
            ->assertJsonPath('data.nome', $payload['nome'])
            ->assertJsonPath('data.preco', $payload['preco'])
            ->assertJsonPath('data.descricao', $payload['descricao']);

        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'nome' => $payload['nome'],
        ]);
    }

    public function test_should_not_create_product_when_user_id_does_not_exist(): void
    {
        $payload = [
            'nome' => 'Produto inválido',
            'preco' => 149.90,
        ];

        $this->callRequest(999999, $payload)
            ->assertNotFound();
    }

    private function callRequest(int $userId, array $payload): TestResponse
    {
        return $this->postJson(route('users.products.store', $userId), $payload);
    }
}
