<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titulo',
        'descricao',
        'preco_venda',
        'custo',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'preco_venda' => 'float',
        'custo' => 'float',
    ];

    public function imagens()
    {
        return $this->hasMany(ProdutoImagem::class);
    }
}

