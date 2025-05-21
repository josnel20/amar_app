<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Models\ProdutoImagem;
use App\Models\Produto;

class ProdutoService
{
    public function criarProduto(array $request, $modo = 'criar')
    {
        $validaModo = ($modo === 'criar' ? 'required' : 'sometimes');

        $validator = Validator::make($request, [
            'titulo' => [$validaModo, 'string', 'max:255'],
            'descricao' => [
                $validaModo, 'string',
                function ($attribute, $value, $fail) {
                    $allowedTags = ['<p>', '<br>', '<b>', '<strong>'];
                    $stripped = strip_tags($value, implode('', $allowedTags));
                    if ($value !== $stripped) {
                        $fail('A descrição contém tags HTML não permitidas.');
                    }
                }
            ],
            'preco_venda' => [$validaModo, 'numeric', 'min:0'],
            'custo' => [$validaModo, 'numeric', 'min:0'],
            'imagem' => ['nullable', 'array', 'min:1'],
        ]);

        if ($validator->fails()) {
            return ['aviso' => $validator->errors()];
        }

        if (isset($request['custo'], $request['preco_venda'])) {
            $preco_minimo = round($request['custo'] * 1.1, 2);
            if ($request['preco_venda'] < $preco_minimo) {
                return [
                    'aviso' => "O preço de venda deve ser ao menos 10% maior que o custo ({$preco_minimo})."
                ];
            }
        }

        return $request;
    }

    public function cadastrarImagem(array $data, $idProduto)
    {
        if (! empty($data)) {
            foreach ($data as $imagem) {
                ProdutoImagem::create(['imagem' => $imagem, 'produto_id' => $idProduto,]);
            }
        }
    }
}