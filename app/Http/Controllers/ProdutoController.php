<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Services\ProdutoService;
use App\Models\ProdutoImagem;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Produto;
use Illuminate\Support\Facades\Log;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $produtos = Produto::where('ativo', true)->with('imagens')->paginate(50);
            if ($produtos->isEmpty()) {
                Log::info('[Poduto - ProdutoController index] - Nenhum produto encontrado', ['request' => $request->all() ?? []]);
                return response()->json(['level' => false, 'message' => 'Nenhum produto encontrado.'], 422);
            }

            return response()->json(['level' => true, 'message' => 'Lista de produtos', 'data' => $produtos], 200);
        } catch (\Throwable $th) {
            Log::error('[Poduto - ProdutoController index] - Lista de prosutos', ['message' => 'Produto atualizado com sucesso', 'erro' => $th->getMessage() ?? []]);
            return response()->json([
                'level' => false,
                'message' => 'Erro ao tentar buscar os produtos',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function create(Request $request, ProdutoService $produtoService)
    {
        try {
            $validated = $produtoService->criarProduto($request->all(), $modo = 'criar');
            if (isset($validated['aviso'])) {
                Log::error('[Poduto - ProdutoController create] - Erro na validação dos dados', ['erro' => $validated]);
                return response()->json([ 'level' => false, 'message' => $validated['aviso']], 422);
            }

            // Criação do produto
            $produto = Produto::create([
                'titulo' => $validated['titulo'],
                'descricao' => $validated['descricao'] ?? '',
                'preco_venda' => $validated['preco_venda'],
                'custo' => $validated['custo'],
            ]);

            if ($request->hasFile('imagens')) {
                $imagem = $produtoService->cadastrarImagem($validated['imagem'], $produto->id);
                if (isset($imagem['aviso'])) {
                    Log::error('[Poduto - ProdutoController create] - ($produtoService->cadastrarImagem) - Erro na validação da imagem', ['erro' => $validated]);
                    return response()->json([ 'level' => false, 'message' => $imagem['aviso']], 422);
                }
            }
           
            return response()->json(['level' => true, 'message' => 'Produto cadastrado com sucesso'], 200);
        } catch (\Throwable $th) {
            Log::error('Poduto - ProdutoController create] - Erro ao criar produto', ['error' => $th->getMessage()]);
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
            Log::error('[Poduto - ProdutoController update] - erro ao editar produto', ['id' => $id ?? '', 'request' => $request->all() ?? []]);
            return response()->json(['level' => false, 'message' => $validated['aviso']], 422);
        }

        try {
            $produto = Produto::find($id);
            if (! $produto) {
                Log::error('[Poduto - ProdutoController update] - produto não localizado', ['id' => $id ?? '', 'request' => $request->all() ?? []]);
                return response()->json(['level' => false, 'message' => 'Produto não encontrado'], 404);
            }

            $produto->update(Arr::only($validated, ['titulo', 'descricao', 'preco_venda', 'custo']));

            return response()->json(['level' => true, 'message' => 'Produto atualizado com sucesso'], 200);

            Log::debug('[Poduto - ProdutoController update] - erro ao editar produto', ['id' => $id, 'message' => 'Produto atualizado com sucesso']);
        } catch (\Throwable $th) {
            Log::error('[Poduto - ProdutoController update] - Erro no processo de edição do produtro', ['id' => $id ?? '', 'request' => $request->all() ?? []]);
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
            Log::error('[Poduto - ProdutoController destroy] - (Produto inativado) Id não localizado', ['id' => $id ?? '']);
            return response()->json(['level' => false, 'message' => 'Produto não Localizado'], 404);
        }
        
        try {
            $produto->update(['ativo' => false]);

            ProdutoImagem::where('produto_id', $id)->update(['ativo' => false]);

            Log::info('[Poduto - ProdutoController destroy] - Produto inativado', ['id' => $id ?? '']);

            return response()->json(['level' => true, 'message' => 'Produto inativado.']);
        } catch (\Throwable $th) {
            $erro = [ 'level' => false, 'message' => 'Erro ao tentar desativar o produto', 'error' => $th->getMessage()];
            Log::error('[Poduto - ProdutoController destroy] - Produto inativado', $erro);
            return response()->json($erro, 500);
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
