<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Only allows users with level 'admin' to pass through.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('pengguna') || session('pengguna')['level'] !== 'admin') {
            return redirect()->route('login')->with('error', 'Akses hanya untuk Admin UMKM.');
        }

        return $next($request);
    }
}
