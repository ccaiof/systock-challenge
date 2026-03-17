<?php

namespace App\Services\User;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ListAllUserService
{
    public function execute(): LengthAwarePaginator
    {
        return DB::table('users')->paginate();
    }
}
