<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $resource): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Se for PRO, não tem limites
        if ($user->isPro()) {
            return $next($request);
        }

        // Limites do plano gratuito
        $limits = [
            'quotes' => 3,
            'clients' => 5,
        ];

        if ($resource === 'quotes' && $user->quotes()->count() >= $limits['quotes']) {
            return redirect()->route('subscription.index')
                ->with('error', 'Você atingiu o limite de 3 orçamentos do plano gratuito. Faça upgrade para o plano Pro para criar orçamentos ilimitados!');
        }

        if ($resource === 'clients' && $user->clients()->count() >= $limits['clients']) {
            return redirect()->route('subscription.index')
                ->with('error', 'Você atingiu o limite de 5 clientes do plano gratuito. Faça upgrade para o plano Pro para ter clientes ilimitados!');
        }

        return $next($request);
    }
}
