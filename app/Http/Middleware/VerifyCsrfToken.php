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
        '/api/tasks/get',     
        '/api/task/get/*',     
        '/api/tasks/add',
        '/api/tasks/update/*',
        '/api/tasks/remove/*'
    ];
}
