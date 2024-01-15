<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Estoque extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto_id',
        'quantidade_inicial',
        'quantidade_disponivel',
        'preco_custo',
        'creat_at'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function getCreatedAtAttribute($creatAt)
    {
        return $creatAt ? Carbon::make($creatAt)->format('d/m/Y') : null;
    }
}
