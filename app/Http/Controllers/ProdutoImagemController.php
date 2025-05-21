<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdutoImagem;

class ProdutoImagemController extends Controller
{
    public function show($id)
    {
        $imgbase64 = ProdutoImagem::find($id);;
        if (! $imgbase64) {
            return response()->json(['level' => false, 'message' => 'imagem não localizada'], 404);
        }
        
        return response()->json(['level' => true, 'message' => 'imagem localizado com sucesso', 'data' => $imgbase64], 200);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'produto_id' => 'required|integer',
            'imagem' => 'required|string',
        ]);

        try {
            $imagem = ProdutoImagem::create([
                'produto_id' => $validated['produto_id'],
                'imagem' => $validated['imagem'],
            ]);

            return response()->json(['level' => true, 'message' => 'Imagem cadastrada com sucesso', 'data' => $imagem], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'level' => false,
                'message' => 'Erro ao cadastrar a imagem',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $imagem = ProdutoImagem::find($id);
        if (! $imagem) {
            return response()->json(['level' => false, 'message' => 'Imagem não Localizado'], 404);
        }
        
        try {
            $imagem->delete();

            return response()->json(['level' => true, 'message' => 'Imagem apagada com sucesso.']);
        } catch (\Throwable $th) {
            return response()->json([
                'level' => false,
                'message' => 'Erro ao tentar Apagar imagem',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
