<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Mail\QuoteStatusNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class PublicQuoteController extends Controller
{
    /**
     * Show the public sharing view for a quote.
     */
    public function show(string $token)
    {
        $quote = Quote::where('share_token', $token)->with(['client', 'items', 'user.companySetting'])->firstOrFail();
        
        return view('quotes.public_show', compact('quote'));
    }

    /**
     * Download the PDF publicly.
     */
    public function pdf(string $token)
    {
        $quote = Quote::where('share_token', $token)->with(['client', 'items', 'user.companySetting'])->firstOrFail();
        
        $setting = $quote->user->companySetting;
        $template = $setting->pdf_template ?? 'classic';

        $allowedTemplates = ['classic', 'modern', 'tabular', 'premium'];
        if (!in_array($template, $allowedTemplates)) {
            $template = 'classic';
        }

        $viewName = "quotes.pdf_templates.{$template}";
        $pdf = Pdf::loadView($viewName, compact('quote'));

        return $pdf->stream("{$quote->quote_number}.pdf");
    }

    /**
     * Approve the quote from the public view.
     */
    public function approve(Request $request, string $token)
    {
        $quote = Quote::where('share_token', $token)->with(['user', 'client'])->firstOrFail();

        if ($quote->status === 'approved') {
            return back()->with('info', 'Este orçamento já foi aprovado anteriormente.');
        }

        $quote->update(['status' => 'approved']);

        // Send Email notification to the owner of the quote
        try {
            Mail::to($quote->user->email)
                ->send(new QuoteStatusNotificationMail($quote, 'approved'));
        } catch (\Exception $e) {
            // Silently fail if mail configuration is missing in dev
            \Illuminate\Support\Facades\Log::warning("Erro ao enviar email de notificação de aprovação: " . $e->getMessage());
        }

        return back()->with('success', 'Orçamento aprovado com sucesso! Entraremos em contato em breve.');
    }

    /**
     * Decline the quote from the public view.
     */
    public function decline(Request $request, string $token)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $quote = Quote::where('share_token', $token)->with(['user', 'client'])->firstOrFail();

        if ($quote->status === 'declined') {
            return back()->with('info', 'Este orçamento já foi marcado como recusado.');
        }

        $quote->status = 'declined';
        if ($request->filled('reason')) {
            $quote->notes = ($quote->notes ? $quote->notes . "\n\n" : '') 
                . "[Feedback do Cliente em " . now()->format('d/m/Y H:i') . "]: " . $request->reason;
        }
        $quote->save();

        // Send Email notification to the owner
        try {
            Mail::to($quote->user->email)
                ->send(new QuoteStatusNotificationMail($quote, 'declined', $request->reason));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Erro ao enviar email de notificação de recusa: " . $e->getMessage());
        }

        return back()->with('success', 'Orçamento recusado com sucesso. Agradecemos pelo seu feedback.');
    }
}
