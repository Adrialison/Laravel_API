<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Excluir todas las rutas de la API
        'api/*',

        // Si usas rutas con prefijos o dominios distintos, puedes agregar:
        // 'sanctum/csrf-cookie',
        // 'api/auth/*',
    ];
}
