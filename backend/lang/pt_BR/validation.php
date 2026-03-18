<?php

// lang/pt_BR/validation.php

return [
    'failed' => 'Erro de validação',
    'min' => [
        'string' => 'O campo :attribute deve conter no mínimo :min caracteres.',
    ],
    'custom' => [
        'cpf' => [
            'validation' => 'CPF inválido',
            'invalid' => 'CPF inválido',
            'unique' => 'CPF já existe'
        ],
        'email' => [
            'required' => 'Email é obrigatório.',
            'unique' => 'Email já existe'
        ],
        'password' => [
            'min' => 'A senha deve conter no mínimo :min caracteres.'
        ]
    ],
    'attributes' => [
        'name' => 'nome',
        'email' => 'email',
        'cpf' => 'CPF',
        'password' => 'senha'
    ]
];
