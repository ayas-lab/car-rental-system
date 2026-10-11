<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$user->hasAnyRole(['agent', 'admin'])) {
            abort(403, 'Accès réservé aux agents.');
        }

        return $next($request);
    }
}