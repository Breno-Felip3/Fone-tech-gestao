<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'preco_venda',
        'tempo_garantia',
        'descricao'
    ];

    public function estoques()
    {
        return $this->hasMany(Estoque::class);
    }
}
