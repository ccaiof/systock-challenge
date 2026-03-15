<?php

namespace Tests\Feature\Rules;

use App\Rules\Cpf;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CpfRuleTest extends TestCase
{
    public function test_should_pass_with_valid_cpf()
    {
        $validator = Validator::make(
            ['cpf' => '488.479.870-83'],
            ['cpf' => [new Cpf()]]
        );

        $this->assertTrue($validator->passes());
    }

    public function test_should_fail_with_invalid_cpf()
    {
        $validator = Validator::make(
            ['cpf' => '111.111.111-11'],
            ['cpf' => [new Cpf()]]
        );

        $this->assertTrue($validator->fails());
    }

    public function test_should_fail_without_numbers()
    {
        $validator = Validator::make(
            ['cpf' => 'abc.def.123-4'],
            ['cpf' => [new Cpf()]]
        );

        $this->assertTrue($validator->fails());
    }
}
