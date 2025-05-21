<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdutoImagemController extends Controller
{
    public function destroy(ProdutoImagem $imagem)
    {
        Storage::disk('public')->delete($imagem->caminho);
        $imagem->delete();

        return response()->json(['message' => 'Imagem deletada com sucesso.']);
    }
}
