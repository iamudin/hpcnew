<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
  public function handle($request, Closure $next)
{
    if (Auth::check() && !auth()->user()->is_active) {
        Auth::logout();
           throw ValidationException::withMessages([
                'data.email' => 'Akun sudah dinonaktifkan, hubungi Admin!',
            ]);
    }
    return $next($request);
}
}
