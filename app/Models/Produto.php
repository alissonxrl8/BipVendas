<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'user_id',
        'codigo_barras',
        'nome',
        'preco_venda',
        'preco_custo',
        'estoque',
        'estoque_minimo',
        'descricao',
        'foto'
    ];
}