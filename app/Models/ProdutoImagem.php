<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoImagem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produto_imagens';
    protected $fillable = [
        'produto_id',
        'imagem', // Só serão permitidas imagens jpg e png, porem no banco salvarei como string base64
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}

