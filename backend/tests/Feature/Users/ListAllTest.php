<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use PHPUnit\Util\Test;
use Tests\TestCase;

class ListAllTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_should_list_users_paginated_structure()
    {
        $this->callRequest()
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta' => [
                    'current_page',
                    'total',
                    'per_page',
                    'from',
                    'last_page',
                ]
            ]);
    }

    public function test_should_list_all_users_paginated()
    {
        User::factory()->count(20)->create();

        $this->callRequest()
            ->assertOk()
            ->assertJsonPath('meta.total', 20)
            ->assertJsonPath('meta.per_page', 15)
            ->assertJsonCount(15, 'data');
    }

    public function test_should_return_empty_list_when_no_data_exist()
    {
        $this->callRequest()
            ->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0);
    }

    private function callRequest(): TestResponse
    {
        return $this->getJson(route('users.index'));
    }
}
