<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Session;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!AuthController::checkAuth()) {
            Session::start();
            return redirect()->route('login');
        }
        return $next($request);
    }
}
