<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireProPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isPro()) {
            return redirect()->route('subscription.index')
                ->with('error', 'Esta funcionalidade é exclusiva do plano Pro. Faça upgrade para acessar!');
        }

        return $next($request);
    }
}
