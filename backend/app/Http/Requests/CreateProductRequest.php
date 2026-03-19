<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $routeUser = $this->route('user');

        if ($routeUser instanceof User) {
            $this->merge(['user_id' => $routeUser->id]);

            return;
        }

        if (is_numeric($routeUser)) {
            $this->merge(['user_id' => (int) $routeUser]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|gt:0',
            'descricao' => 'nullable|string',
        ];
    }
}
