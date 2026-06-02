<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Assinaturas (Paywall) ──────────────────────────────────────────────────
    Route::get('/planos', [\App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/assinatura/checkout', [\App\Http\Controllers\SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/assinatura/portal', [\App\Http\Controllers\SubscriptionController::class, 'billingPortal'])->name('subscription.portal');

    // ── Clientes ───────────────────────────────────────────────────────────────
    // Limite de criação para plano gratuito
    Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create')->middleware('plan.limits:clients');
    Route::post('clients', [ClientController::class, 'store'])->name('clients.store')->middleware('plan.limits:clients');
    Route::resource('clients', ClientController::class)->except(['create', 'store']);

    // ── Serviços ───────────────────────────────────────────────────────────────
    Route::resource('services', ServiceController::class);

    // ── Orçamentos ─────────────────────────────────────────────────────────────
    // Limite de criação para plano gratuito
    Route::get('quotes/create', [QuoteController::class, 'create'])->name('quotes.create')->middleware('plan.limits:quotes');
    Route::post('quotes', [QuoteController::class, 'store'])->name('quotes.store')->middleware('plan.limits:quotes');
    Route::post('quotes/{quote}/duplicate', [QuoteController::class, 'duplicate'])->name('quotes.duplicate')->middleware('plan.limits:quotes');
    Route::resource('quotes', QuoteController::class)->except(['create', 'store']);
    
    Route::get('quotes/{quote}/pdf', [QuoteController::class, 'pdf'])->name('quotes.pdf');
    Route::get('quotes/{quote}/receipt', [QuoteController::class, 'receipt'])->name('quotes.receipt');
    
    // Envio de email é exclusivo do plano PRO
    Route::post('quotes/{quote}/send-email', [QuoteController::class, 'sendEmail'])->name('quotes.sendEmail')->middleware('pro.only');

    // ── Configurações ──────────────────────────────────────────────────────────
    // Customização de logotipo e cores é exclusivo PRO
    Route::middleware('pro.only')->group(function() {
        Route::get('settings', [CompanySettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [CompanySettingController::class, 'update'])->name('settings.update');
        Route::get('settings/preview/{template}', [CompanySettingController::class, 'previewTemplate'])->name('settings.template.preview');
        Route::get('settings/receipt-preview/{template}', [CompanySettingController::class, 'previewReceiptTemplate'])->name('settings.receipt.preview');
    });
});

// ── Rotas Públicas de Compartilhamento (Links de Compartilhamento Ilimitados) 
Route::get('/q/{token}', [\App\Http\Controllers\PublicQuoteController::class, 'show'])->name('public.quote.show');
Route::get('/q/{token}/pdf', [\App\Http\Controllers\PublicQuoteController::class, 'pdf'])->name('public.quote.pdf');
Route::post('/q/{token}/approve', [\App\Http\Controllers\PublicQuoteController::class, 'approve'])->name('public.quote.approve');
Route::post('/q/{token}/decline', [\App\Http\Controllers\PublicQuoteController::class, 'decline'])->name('public.quote.decline');

require __DIR__.'/auth.php';

