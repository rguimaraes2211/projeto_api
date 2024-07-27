<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 't_usuario';

    protected $fillable = [
        'nome', 'email', 'senha'
    ];

    protected $hidden = [
        'senha',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['senha'] = bcrypt($password);
    }
}
