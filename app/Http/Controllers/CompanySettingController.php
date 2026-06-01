<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller
{
    /**
     * Show the form for editing the company settings.
     */
    public function edit()
    {
        $setting = Auth::user()->companySetting;

        if (!$setting) {
            // Instantiate with defaults if not present
            $setting = new CompanySetting([
                'primary_color' => '#2563eb',
                'secondary_color' => '#1e3a8a',
            ]);
        }

        return view('settings.edit', compact('setting'));
    }

    /**
     * Update the company settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'primary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'secondary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'signature_text' => 'nullable|string',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'signature_path' => 'nullable|image|max:2048',
            'pdf_template'   => 'nullable|string|in:classic,modern,tabular,premium',
            'receipt_template' => 'nullable|string|in:classic,modern,tabular',
        ]);

        $setting = Auth::user()->companySetting;
        $data = $request->only([
            'company_name',
            'document',
            'email',
            'phone',
            'address',
            'primary_color',
            'secondary_color',
            'signature_text',
            'pdf_template',
            'receipt_template',
        ]);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($setting && $setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            // Store new logo
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $path;
        }

        // Handle Signature Upload
        if ($request->hasFile('signature')) {
            // Delete old signature if it exists
            if ($setting && $setting->signature_path) {
                Storage::disk('public')->delete($setting->signature_path);
            }
            // Store new signature
            $path = $request->file('signature')->store('signatures', 'public');
            $data['signature_path'] = $path;
        }

        CompanySetting::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        return redirect()->route('settings.edit')
            ->with('success', 'Configurações da empresa atualizadas com sucesso!');
    }

    /**
     * Preview the PDF template.
     */
    public function previewTemplate($template)
    {
        $allowedTemplates = ['classic', 'modern', 'tabular', 'premium'];
        if (!in_array($template, $allowedTemplates)) {
            abort(404);
        }

        $user = Auth::user();
        $setting = $user->companySetting ?? new CompanySetting();

        // Create a dummy client
        $client = new \App\Models\Client([
            'name' => 'Cliente de Demonstração LTDA',
            'document' => '12.345.678/0001-90',
            'email' => 'contato@clientedemo.com',
            'phone' => '(11) 98765-4321',
            'address' => 'Av. Paulista, 1000 - São Paulo, SP'
        ]);

        // Create a dummy quote
        $quote = new \App\Models\Quote([
            'quote_number' => 'ORC-2026-9999',
            'status' => 'draft',
            'issue_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(15)->format('Y-m-d'),
            'discount' => 150.00,
            'total_amount' => 3850.00,
            'notes' => 'Este é um documento de pré-visualização. Os dados apresentados são fictícios e servem apenas para demonstrar o layout do modelo selecionado.',
        ]);

        // Mock relationships
        $quote->setRelation('client', $client);
        $quote->setRelation('user', $user);

        // Dummy items
        $items = collect([
            new \App\Models\QuoteItem([
                'description' => 'Desenvolvimento de Landing Page',
                'quantity' => 1,
                'unit_price' => 2500.00,
                'total_price' => 2500.00
            ]),
            new \App\Models\QuoteItem([
                'description' => 'Hospedagem e Manutenção Trimestral',
                'quantity' => 3,
                'unit_price' => 500.00,
                'total_price' => 1500.00
            ])
        ]);

        $quote->setRelation('items', $items);

        $viewName = "quotes.pdf_templates.{$template}";

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewName, compact('quote', 'setting'));

        return $pdf->stream("preview-{$template}.pdf");
    }

    /**
     * Preview a receipt template.
     */
    public function previewReceiptTemplate($template)
    {
        $validTemplates = ['classic', 'modern', 'tabular'];
        if (!in_array($template, $validTemplates)) {
            abort(404);
        }

        // Create a mock user
        $user = new \App\Models\User([
            'name' => 'Usuário Demonstração',
            'email' => 'demo@exemplo.com.br',
        ]);

        // Create a mock setting
        $setting = new CompanySetting([
            'company_name' => 'Empresa Demo LTDA',
            'document' => '12.345.678/0001-90',
            'email' => 'contato@clientedemo.com',
            'phone' => '(11) 98765-4321',
            'address' => 'Av. Paulista, 1000 - São Paulo, SP',
            'primary_color' => '#2563eb', // blue-600
            'secondary_color' => '#1e40af',
            'signature_text' => 'Assinatura Demo',
            'receipt_template' => $template,
        ]);
        
        // Let's use the actual logged in user's setting if it exists, so the preview shows their actual logo and colors
        if (Auth::check() && Auth::user()->companySetting) {
            $realSetting = Auth::user()->companySetting;
            $setting->company_name = $realSetting->company_name ?? $setting->company_name;
            $setting->document = $realSetting->document ?? $setting->document;
            $setting->email = $realSetting->email ?? $setting->email;
            $setting->phone = $realSetting->phone ?? $setting->phone;
            $setting->address = $realSetting->address ?? $setting->address;
            $setting->primary_color = $realSetting->primary_color ?? $setting->primary_color;
            $setting->secondary_color = $realSetting->secondary_color ?? $setting->secondary_color;
            $setting->logo_path = $realSetting->logo_path;
            $setting->signature_path = $realSetting->signature_path;
            $setting->signature_text = $realSetting->signature_text ?? $setting->signature_text;
        }

        $user->setRelation('companySetting', $setting);

        // Create mock client
        $client = new \App\Models\Client([
            'name' => 'Cliente Exemplo',
            'document' => '123.456.789-00',
            'email' => 'cliente@email.com',
            'phone' => '(11) 91111-2222',
            'address' => 'Rua Exemplo, 123 - Bairro',
        ]);

        // Create mock items
        $item1 = new \App\Models\QuoteItem([
            'description' => 'Serviço de Desenvolvimento',
            'quantity' => 1,
            'unit_price' => 2500.00,
            'total_price' => 2500.00,
        ]);
        
        $item2 = new \App\Models\QuoteItem([
            'description' => 'Manutenção Mensal',
            'quantity' => 2,
            'unit_price' => 500.00,
            'total_price' => 1000.00,
        ]);

        $items = collect([$item1, $item2]);

        // Create mock quote
        $quote = new \App\Models\Quote([
            'quote_number' => 'ORC-2026-9999',
            'issue_date' => now(),
            'due_date' => now()->addDays(15),
            'status' => 'draft',
            'total_amount' => 3500.00,
            'discount' => 0.00,
            'notes' => "Este é um documento de pré-visualização.\nOs dados são fictícios.",
        ]);

        // Set relations
        $quote->setRelation('user', $user);
        $quote->setRelation('client', $client);
        $quote->setRelation('items', $items);
        
        $valorExtenso = \App\Utils\NumberToWords::converterMonetario($quote->total_amount);

        // Load the specific receipt view
        $viewName = "quotes.receipt_templates.{$template}";
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewName, compact('quote', 'setting', 'valorExtenso'));

        return $pdf->stream("preview-recibo-{$template}.pdf");
    }
}
