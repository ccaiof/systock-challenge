<?php

namespace App\Http\Requests;

use App\DTOs\User\CreateUserRequestDTO;
use App\Rules\Cpf;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'cpf' => ['required', 'string', 'unique:users', new Cpf()],
            'password' => 'required|min:6'
        ];
    }

    public function toDTO(): CreateUserRequestDTO
    {
        return CreateUserRequestDTO::fromArray($this->validated());
    }
}
