<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 't_cliente';

    protected $fillable = [
        'nome', 'cpf', 'nascimento', 'telefone', 'estado', 'cidade', 'foto',
    ];

}
