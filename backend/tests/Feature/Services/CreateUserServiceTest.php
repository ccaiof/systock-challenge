<?php

namespace Tests\Feature\Services;

use App\DTOs\User\CreateUserRequestDTO;
use App\Exceptions\UserCreationException;
use App\Models\User;
use App\Services\User\CreateUserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserServiceTest extends TestCase
{
    use RefreshDatabase;

    private CreateUserService $createUserService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createUserService = new CreateUserService();
    }

    public function test_should_return_the_created_user_when_payload_is_valid()
    {
        $name = fake()->name();
        $email = fake()->email();
        $cpf = fake()->cpf();

        $payload = new CreateUserRequestDTO(
            $name,
            $email,
            $cpf,
            fake()->password()
        );

        $response = $this->createUserService->execute($payload);

        $this->assertContains($name, $response->toArray());
    }

    public function test_should_return_user_creation_exception_when_existing_user()
    {
        $cpf = '48847987083';
        $email = fake()->email();

        User::factory()->create([
            'cpf' => $cpf,
            'email' => $email
        ]);

        $userInvalid = new CreateUserRequestDTO(
            fake()->name(),
            $email,
            $cpf,
            fake()->password()
        );

        $this->expectException(UserCreationException::class);
        $this->expectExceptionMessage(__('errors.user.already_exists'));

        $this->createUserService->execute($userInvalid);
    }

    public function test_should_return_user_creation_exception_when_email_already_exists()
    {
        $existingUser = User::factory()->create();

        $userInvalid = new CreateUserRequestDTO(
            fake()->name(),
            $existingUser->email,
            fake()->cpf(),
            fake()->password()
        );

        $this->expectException(UserCreationException::class);
        $this->expectExceptionMessage(__('errors.user.already_exists'));

        $this->createUserService->execute($userInvalid);
    }
}
