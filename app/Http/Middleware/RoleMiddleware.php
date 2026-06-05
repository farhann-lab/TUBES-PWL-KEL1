<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Paksa logout jika akun dinonaktifkan
        if (!auth()->user()->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->withErrors([
                'email' => 'Akun ini telah dinonaktifkan. Hubungi Manager.',
            ]);
        }

        if (!in_array(auth()->user()->role, $roles)) {
            return match (auth()->user()->role) {
                'manager' => redirect()->route('manager.dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
                'kasir' => redirect()->route('kasir.transactions.index'),
                default => redirect('/login'),
            };
        }

        return $next($request);
    }
}
