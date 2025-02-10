<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

class CheckLevel
{
    public function handle(Request $request, Closure $next, ...$levels)
    {
        if (!AuthController::checkAuth()) {
            return redirect()->route('login');
        }

        $userLevel = AuthController::userLevel();
        if (!in_array($userLevel, $levels)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
