<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => User::all() ?? null
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::error('[User - UserController update] - Edição de informações', ['id' => $id ?? '', 'request' => $request->all() ?? []]);
        $user = User::find($id);
        if (!$user) {
            Log::error('[User - UserController update] - Edição de informações', ['id' => $id ?? '', 'request' => $request->all() ?? []]);
            return response()->json(['idUser' => $id, 'level' => false, 'message' => 'Usuario não localizado' ], 404);
        }
    
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);
    
        try {
            $user->update([ 'name' => $validated['name'], 'email' => $validated['email']]);
    
            Log::debug('[User - UserController update] - Edição de informações', ['id' => $id, 'level' => false, 'message' => 'Dados do usuário alterados com sucesos']);
            return response()->json([ 'message' => 'Perfil atualizado com sucesso'], 200);
        } catch (\Throwable $th) {
            Log::debug('[User - UserController update] - Edição de informações', ['id' => $id, 'level' => false, 'message' => 'Dados do usuário alterados com sucesos']);
            return response()->json(['idUser' => $id, 'message' => 'Erro ao atualizar dados de Perfil', 'error' => $th->getMessage()], 500);
        }
    }
    
}
