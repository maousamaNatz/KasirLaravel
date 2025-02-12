<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $level)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userLevel = Auth::user()->level->nama_level;
        
        if ($userLevel !== $level) {
            return redirect()->route("{$userLevel}.dashboard")
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }

        return $next($request);
    }
}
