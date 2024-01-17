<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EstoqueEntrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'observacao',
        'total_entrada',
        'creat_at'
    ];

    //retorna os itens da entrada
    public function itens()
    {
        return $this->hasMany(EstoqueEntradaItem::class);
    }
    
    public function getCreatedAtAttribute($creatAt)
    {
        return $creatAt ? Carbon::make($creatAt)->format('d/m/Y') : null;
    }

    public function getTotalEntradaAttribute($valor)
    {
        return $this->attributes['total_entrada'] = number_format($valor, 2, ',', '.');
    }
}
