<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActiveStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Проверяем, аутентифицирован ли пользователь
        if (Auth::check()) {
            // Проверяем, активен ли пользователь (предположим, у вас есть поле "active" в таблице пользователей)
            if (!Auth::user()->active) {
                // Пользователь не активен, выходим из сессии
                Auth::logout();
                // Дополнительно, вы можете добавить сообщение или редирект на страницу входа
                return redirect()->route('login')->with('status', 'Ваш аккаунт не активен. Пожалуйста, войдите снова.');
            }
        }

        return $next($request);
    }
}
