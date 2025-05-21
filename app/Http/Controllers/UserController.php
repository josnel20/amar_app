<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => User::all() ?? null, // apnas para teste garantindo que os dados estão sendo buscados do banco
        ]);
    }
}
