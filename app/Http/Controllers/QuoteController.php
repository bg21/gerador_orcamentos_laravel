<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Mail\QuoteMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $quotes = Quote::where('user_id', $userId)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate basic statistics for the dashboard/summary
        $stats = [
            'total' => Quote::where('user_id', $userId)->count(),
            'pending' => Quote::where('user_id', $userId)->where('status', 'draft')->count(),
            'sent' => Quote::where('user_id', $userId)->where('status', 'sent')->count(),
            'approved' => Quote::where('user_id', $userId)->where('status', 'approved')->count(),
            'total_value' => Quote::where('user_id', $userId)->where('status', 'approved')->sum('total_amount'),
        ];

        return view('quotes.index', compact('quotes', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userId = Auth::id();
        $clients = Client::where('user_id', $userId)->orderBy('name')->get();
        $services = Service::where('user_id', $userId)->orderBy('name')->get();

        return view('quotes.create', compact('clients', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'client_id' => [
                'required',
                'exists:clients,id',
                function ($attribute, $value, $fail) use ($userId) {
                    if (!Client::where('id', $value)->where('user_id', $userId)->exists()) {
                        $fail('O cliente selecionado é inválido.');
                    }
                }
            ],
            'status' => 'required|in:draft,sent,approved,declined',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'discount' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|string',
        ]);

        // Parse discount (BRL format)
        $discount = $request->filled('discount') ? $this->sanitizeMoney($request->discount) : 0.00;

        DB::beginTransaction();
        try {
            // Generate sequence number
            $year = now()->year;
            $count = Quote::where('user_id', $userId)->whereYear('created_at', $year)->count();
            $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            $quoteNumber = "ORC-{$year}-{$sequence}";

            $quote = Quote::create([
                'user_id' => $userId,
                'client_id' => $request->client_id,
                'quote_number' => $quoteNumber,
                'status' => $request->status,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'discount' => $discount,
                'total_amount' => 0.00, // will calculate below
                'notes' => $request->notes,
            ]);

            $totalAmount = 0.00;

            foreach ($request->items as $item) {
                $unitPrice = $this->sanitizeMoney($item['unit_price']);
                $totalPrice = $item['quantity'] * $unitPrice;
                $totalAmount += $totalPrice;

                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
            }

            // Subtract discount
            $totalAmount = max(0.00, $totalAmount - $discount);
            $quote->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('quotes.index')
                ->with('success', "Orçamento {$quoteNumber} criado com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ocorreu um erro ao salvar o orçamento: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        $quote->load(['client', 'items']);

        return view('quotes.show', compact('quote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        $userId = Auth::id();
        $clients = Client::where('user_id', $userId)->orderBy('name')->get();
        $services = Service::where('user_id', $userId)->orderBy('name')->get();
        $quote->load('items');

        return view('quotes.edit', compact('quote', 'clients', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quote $quote)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        $userId = Auth::id();

        $request->validate([
            'client_id' => [
                'required',
                'exists:clients,id',
                function ($attribute, $value, $fail) use ($userId) {
                    if (!Client::where('id', $value)->where('user_id', $userId)->exists()) {
                        $fail('O cliente selecionado é inválido.');
                    }
                }
            ],
            'status' => 'required|in:draft,sent,approved,declined',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'discount' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|string',
        ]);

        $discount = $request->filled('discount') ? $this->sanitizeMoney($request->discount) : 0.00;

        DB::beginTransaction();
        try {
            $quote->update([
                'client_id' => $request->client_id,
                'status' => $request->status,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'discount' => $discount,
                'notes' => $request->notes,
            ]);

            // Clear existing items and recreate
            $quote->items()->delete();

            $totalAmount = 0.00;

            foreach ($request->items as $item) {
                $unitPrice = $this->sanitizeMoney($item['unit_price']);
                $totalPrice = $item['quantity'] * $unitPrice;
                $totalAmount += $totalPrice;

                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
            }

            $totalAmount = max(0.00, $totalAmount - $discount);
            $quote->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('quotes.index')
                ->with('success', "Orçamento {$quote->quote_number} atualizado com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ocorreu um erro ao atualizar o orçamento: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        $quoteNumber = $quote->quote_number;
        $quote->delete(); // Cascading delete is handled by database foreign key

        return redirect()->route('quotes.index')
            ->with('success', "Orçamento {$quoteNumber} excluído com sucesso!");
    }

    /**
     * Send the quote by e-mail to the client.
     */
    public function sendEmail(Request $request, Quote $quote)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'recipient_email'  => 'required|email',
            'custom_message'   => 'nullable|string|max:1000',
        ]);

        $quote->load(['client', 'items', 'user.companySetting']);

        try {
            Mail::to($request->recipient_email)
                ->send(new QuoteMail($quote, $request->input('custom_message', '')));

            // Automatically move status from draft to sent
            if ($quote->status === 'draft') {
                $quote->update(['status' => 'sent']);
            }

            return redirect()->route('quotes.show', $quote)
                ->with('success', "Orçamento {$quote->quote_number} enviado com sucesso para {$request->recipient_email}!");
        } catch (\Exception $e) {
            return back()->with('error', 'Não foi possível enviar o e-mail: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for the specified quote.
     */
    public function pdf(Quote $quote)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        $quote->load(['client', 'items', 'user.companySetting']);

        $setting = $quote->user->companySetting;
        $template = $setting->pdf_template ?? 'classic';

        $allowedTemplates = ['classic', 'modern', 'tabular', 'premium'];
        if (!in_array($template, $allowedTemplates)) {
            $template = 'classic';
        }

        $viewName = "quotes.pdf_templates.{$template}";

        // Generate and stream PDF
        $pdf = Pdf::loadView($viewName, compact('quote'));

        return $pdf->stream("{$quote->quote_number}.pdf");
    }

    /**
     * Generate PDF Receipt for the specified quote.
     */
    public function receipt(Quote $quote)
    {
        if ($quote->user_id !== Auth::id()) {
            abort(403);
        }

        $quote->load(['client', 'items', 'user.companySetting']);
        $setting = $quote->user->companySetting;
        
        $valorExtenso = \App\Utils\NumberToWords::converterMonetario($quote->total_amount);

        // Determine template
        $template = $setting->receipt_template ?? 'modern';
        $viewName = "quotes.receipt_templates.{$template}";

        // Load the view
        $pdf = Pdf::loadView($viewName, compact('quote', 'setting', 'valorExtenso'));

        return $pdf->stream("recibo-{$quote->quote_number}.pdf");
    }

    /**
     * Helper to sanitize monetary strings (BRL) to float decimal.
     */
    private function sanitizeMoney($value)
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        
        // Remove currency symbols, spaces and thousands separators (.)
        $clean = str_replace(['R$', ' ', '.'], '', $value);
        // Replace decimal separators (,) with standard dot (.)
        $clean = str_replace(',', '.', $clean);
        
        return (float) $clean;
    }
}
