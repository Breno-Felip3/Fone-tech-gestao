<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstoqueEntradaItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'estoque_entrada_itens';
    protected $dates = ['deleted_at'];
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

