<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Services\ProdutoService;
use App\Models\ProdutoImagem;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Produto;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $produtos = Produto::with('imagens')->paginate(50);
            if ($produtos->isEmpty()) {
                return response()->json(['level' => false, 'message' => 'Nenhum produto encontrado.'], 422);
            }

            return response()->json(['level' => true, 'message' => 'Lista de produtos', 'data' => $produtos], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'level' => false,
                'message' => 'Erro ao tentar buscar os produtos',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function create(Request $request, ProdutoService $produtoService)
    {
        $validated = $produtoService->criarProduto($request->all(), $modo = 'criar');
        if (isset($validated['aviso'])) {
            return response()->json([ 'level' => false, 'message' => $validated['aviso']], 422);
        }

        try {
            $produto = Produto::create([
                'titulo' => $validated['titulo'],
                'descricao' => $validated['descricao'],
                'preco_venda' => $validated['preco_venda'],
                'custo' => $validated['custo'],
            ]);

            $imagem = $produtoService->cadastrarImagem($validated['caminho'], $produto->id);
            if (isset($imagem['aviso'])) {
                return response()->json([ 'level' => false, 'message' => $imagem['aviso']], 422);
            }

            return response()->json(['level' => true, 'message' => 'Produto cadastrado com sucesso'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'level' => false,
                'message' => 'Ocorreu um erro ao tentar cadastrar o produto',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, ProdutoService $produtoService, $id)
    {
        $validated = $produtoService->criarProduto($request->all(), $modo = null);
        if (isset($validated['aviso'])) {
            return response()->json(['level' => false, 'message' => $validated['aviso']], 422);
        }

        try {
            $produto = Produto::find($id);
            if (! $produto) {
                return response()->json(['level' => false, 'message' => 'Produto não encontrado'], 404);
            }

            $produto->update(Arr::only($validated, ['titulo', 'descricao', 'preco_venda', 'custo']));

            return response()->json(['level' => true, 'message' => 'Produto atualizado com sucesso'], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'level' => false,
                'message' => 'Erro ao tentar atualizar o produto',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $produto = Produto::find($id);
        if (! $produto) {
            return response()->json(['level' => false, 'message' => 'Produto não Localizado'], 404);
        }
        
        try {
            $produto->update(['ativo' => false]);

            ProdutoImagem::where('produto_id', $id)->update(['ativo' => false]);

            return response()->json(['level' => true, 'message' => 'Produto inativado.']);
        } catch (\Throwable $th) {
            return response()->json([
                'level' => false,
                'message' => 'Erro ao tentar desativar o produto',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $produto = Produto::with('imagens')->find($id);;
        
        if (! $produto) {
            return response()->json(['level' => false, 'message' => 'Produto não encontrado'], 404);
        }
        
        return response()->json(['level' => true, 'message' => 'Produto localizado com sucesso', 'data' => $produto], 200);
    }
}
