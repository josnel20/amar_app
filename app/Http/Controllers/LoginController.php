<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            Log::error('[LOGIN - LoginController store]', ['level' => false, 'message' => 'Dados de login inválidos.', 'request' => $request->all(), 'errors' => $validator->errors()]);
            return response()->json(['level' => false, 'message' => 'Dados de login inválidos.', 'errors' => $validator->errors()], 422);
        }

        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                Log::error('[LOGIN - LoginController store]', ['level' => false, 'message' => 'Credenciais inválidas.']);
                return response()->json([ 'level' => false, 'message' => 'Credenciais inválidas.'], 401);
            }

            $request->session()->regenerate();

            $user = Auth::user();

            return response()->json(['level' => true,'message' => 'Login realizado com sucesso!','data' => $user,'token' => $user->createToken('auth_token')->plainTextToken], 200);
        } catch (\Throwable $th) {
            Log::error('[LOGIN - LoginController store]', ['level' => false,'message' => 'Ocorreu um erro ao tentar fazer login.','error' => $th->getMessage()]);
            return response()->json(['level' => false,'message' => 'Ocorreu um erro ao tentar fazer login.','error' => $th->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $tokenString = $request->bearerToken();
            if ($tokenString) {
                PersonalAccessToken::where('token', hash('sha256', $tokenString))->delete();
            }

            return response()->json([ 'message' => 'Logout realizado com sucesso.'], 200);
        } catch (\Throwable $th) {
            Log::error('Erro no logout', ['error' => $th->getMessage(),'line' => $th->getLine(),'file' => $th->getFile() ]);

            return response()->json([
                'message' => 'Erro ao fazer logout.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
