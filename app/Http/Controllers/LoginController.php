<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['level' => false, 'message' => 'Dados de login inválidos.', 'errors' => $validator->errors()], 422);
        }

        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([ 'level' => false, 'message' => 'Credenciais inválidas.'], 401);
            }

            $user = Auth::user();

            return response()->json(['level' => true,'message' => 'Login realizado com sucesso!','data' => $user,'token' => $user->createToken('auth_token')->plainTextToken], 200);
        } catch (\Throwable $th) {
            return response()->json(['level' => false,'message' => 'Ocorreu um erro ao tentar fazer login.','error' => $th->getMessage()], 500);
        }
    }
}
