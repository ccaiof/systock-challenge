<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $result = DB::select('SELECT users.id, users.name, COUNT(products.id) AS total_products, ROUND(AVG(products.preco), 2) AS avg_price FROM users LEFT JOIN products ON users.id = products.user_id GROUP BY users.id, users.name ORDER BY users.id');
        return response()->json(['message' => 'Report generated successfully', 'data' => $result]);
    }
}
