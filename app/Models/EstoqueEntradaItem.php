<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstoqueEntradaItem extends Model
{
    use HasFactory;

    protected $table = 'estoque_entrada_itens';
    protected $fillable = [
        'estoque_entrada_id',
        'produto_id',
        'quantidade'
    ];

    //Retorna a entrada correspondente do item
    public function entrada()
    {
        return $this->belongsTo(EstoqueEntrada::class);
    }

    public function estoqueSaldo() 
    {
        return $this->hasOne(EstoqueSaldo::class, 'produto_id', 'produto_id');
    }
}

