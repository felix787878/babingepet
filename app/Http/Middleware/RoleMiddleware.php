<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        //Cek jika user tidak login atau tidak memiliki role yang sesuai
        if (!$user || !in_array($user->role, $roles)) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Anda tidak memilikin izin akses halaman tersebut');
        }

        return $next($request);
    }
}