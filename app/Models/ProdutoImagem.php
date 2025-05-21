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
        'caminho',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}

