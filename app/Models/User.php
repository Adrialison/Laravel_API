<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nombre',
        'correo',
        'contraseña',
        'direccion',
        'telefono',
        'rol',
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    // Para autenticación (Laravel usa "password")
    public function getAuthPassword()
    {
        return $this->contraseña;
    }
}
