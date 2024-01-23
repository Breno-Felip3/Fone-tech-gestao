<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nome',
        'preco_custo',
        'preco_venda',
        'tempo_garantia',
        'descricao',
    ];

    public function estoque()
    {
        return $this->hasOne(EstoqueSaldo::class);
    }

    //Retorna as entradas do produto
    public function entradas()
    {
        return $this->hasMany(EstoqueEntrada::class);
    }    
    
}
