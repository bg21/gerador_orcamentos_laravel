<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Exibe a página de Planos e Preços.
     */
    public function index()
    {
        return view('subscription.index');
    }

    /**
     * Redireciona o usuário para o Checkout do Stripe.
     */
    public function checkout(Request $request)
    {
        // Se já for Pro, redireciona para o portal ou dashboard
        if ($request->user()->isPro()) {
            return redirect()->route('dashboard')->with('info', 'Você já é um assinante Pro!');
        }

        $priceId = config('services.stripe.pro_price_id', env('STRIPE_PRO_PRICE_ID'));

        if (!$priceId) {
            return back()->with('error', 'O plano Pro ainda não está configurado no sistema. Contate o suporte.');
        }

        return $request->user()
            ->newSubscription('default', $priceId)
            ->checkout([
                'success_url' => route('dashboard') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('subscription.index'),
            ]);
    }

    /**
     * Redireciona o usuário para o Portal de Faturamento do Stripe.
     */
    public function billingPortal(Request $request)
    {
        if (!$request->user()->isPro()) {
            return redirect()->route('subscription.index');
        }

        return $request->user()->redirectToBillingPortal(route('dashboard'));
    }
}
