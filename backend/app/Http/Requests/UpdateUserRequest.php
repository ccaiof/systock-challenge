<?php

namespace App\Http\Requests;

use App\DTOs\User\UpdateUserRequestDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email' => 'sometimes|email|unique:users',
        ];
    }

    public function toDTO(): UpdateUserRequestDTO
    {
        return UpdateUserRequestDTO::fromArray($this->validated());
    }
}
