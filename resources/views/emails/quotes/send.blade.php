<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento {{ $quote->quote_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f3f4f6;
            color: #374151;
            line-height: 1.6;
        }
        .wrapper {
            max-width: 620px;
            margin: 32px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .header {
            background: {{ $quote->user->companySetting?->primary_color ?? '#4f46e5' }};
            padding: 40px 40px 32px;
            text-align: center;
        }
        .header-company {
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .header-title {
            color: #ffffff;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header-subtitle {
            color: rgba(255,255,255,0.75);
            font-size: 14px;
            margin-top: 4px;
        }
        .body {
            padding: 40px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }
        .intro-text {
            font-size: 15px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .custom-message {
            background: #f9fafb;
            border-left: 3px solid {{ $quote->user->companySetting?->primary_color ?? '#4f46e5' }};
            padding: 14px 18px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
            font-size: 14px;
            color: #374151;
            font-style: italic;
            white-space: pre-line;
        }
        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 28px 0;
        }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 16px;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 28px;
        }
        .detail-item label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            margin-bottom: 3px;
        }
        .detail-item span {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-draft    { background: #f1f5f9; color: #475569; }
        .status-sent     { background: #dbeafe; color: #1d4ed8; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-declined { background: #fee2e2; color: #991b1b; }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .items-table thead tr {
            background: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
        }
        .items-table thead th {
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            text-align: left;
        }
        .items-table thead th.text-right { text-align: right; }
        .items-table thead th.text-center { text-align: center; }
        .items-table tbody tr { border-bottom: 1px solid #f3f4f6; }
        .items-table tbody td {
            padding: 11px 12px;
            color: #374151;
        }
        .items-table tbody td.text-right { text-align: right; }
        .items-table tbody td.text-center { text-align: center; }
        .items-table tbody td.font-semibold { font-weight: 600; color: #111827; }
        .totals-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 28px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 14px;
            color: #6b7280;
        }
        .totals-row.discount { color: #16a34a; font-weight: 600; }
        .totals-row.total {
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
            margin-top: 4px;
            margin-bottom: 0;
            font-weight: 800;
            font-size: 18px;
            color: #111827;
        }
        .totals-row.total .amount {
            color: {{ $quote->user->companySetting?->primary_color ?? '#4f46e5' }};
        }
        .notes-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 16px;
            font-size: 13px;
            color: #78350f;
            margin-bottom: 28px;
            white-space: pre-line;
        }
        .cta-box {
            text-align: center;
            background: #f9fafb;
            border-radius: 10px;
            padding: 24px;
            margin-top: 8px;
        }
        .cta-text {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        .footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 24px 40px;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 4px;
        }
        .footer a {
            color: {{ $quote->user->companySetting?->primary_color ?? '#4f46e5' }};
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- ===== HEADER ===== --}}
    <div class="header">
        @php
            $setting     = $quote->user->companySetting;
            $companyName = $setting?->company_name ?? $quote->user->name;
        @endphp
        <div class="header-company">{{ $companyName }}</div>
        <div class="header-title">Proposta Comercial</div>
        <div class="header-subtitle">{{ $quote->quote_number }}</div>
    </div>

    {{-- ===== BODY ===== --}}
    <div class="body">

        <p class="greeting">Olá, {{ $quote->client->name }}!</p>
        <p class="intro-text">
            Conforme combinado, segue em anexo a proposta comercial da
            <strong>{{ $companyName }}</strong> para sua apreciação.
        </p>

        @if($customMessage)
            <div class="custom-message">{{ $customMessage }}</div>
        @endif

        <hr class="divider">

        {{-- Detalhes do orçamento --}}
        <p class="section-title">Detalhes da Proposta</p>
        <div class="detail-grid">
            <div class="detail-item">
                <label>Número</label>
                <span>{{ $quote->quote_number }}</span>
            </div>
            <div class="detail-item">
                <label>Status</label>
                @php
                    $statusMap = [
                        'draft'    => ['label' => 'Rascunho',  'class' => 'status-draft'],
                        'sent'     => ['label' => 'Enviado',   'class' => 'status-sent'],
                        'approved' => ['label' => 'Aprovado',  'class' => 'status-approved'],
                        'declined' => ['label' => 'Recusado',  'class' => 'status-declined'],
                    ];
                    $s = $statusMap[$quote->status] ?? ['label' => $quote->status, 'class' => 'status-draft'];
                @endphp
                <span class="status-badge {{ $s['class'] }}">{{ $s['label'] }}</span>
            </div>
            <div class="detail-item">
                <label>Data de Emissão</label>
                <span>{{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}</span>
            </div>
            @if($quote->due_date)
            <div class="detail-item">
                <label>Válido Até</label>
                <span>{{ \Carbon\Carbon::parse($quote->due_date)->format('d/m/Y') }}</span>
            </div>
            @endif
        </div>

        <hr class="divider">

        {{-- Itens --}}
        <p class="section-title">Serviços / Itens</p>
        @php $subtotal = 0; @endphp
        <table class="items-table">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th class="text-center">Qtd</th>
                    <th class="text-right">Unit.</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->items as $item)
                    @php $subtotal += $item->total_price; @endphp
                    <tr>
                        <td class="font-semibold">{{ $item->description }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                        <td class="text-right font-semibold">R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totais --}}
        <div class="totals-box">
            <div class="totals-row">
                <span>Subtotal</span>
                <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
            </div>
            @if($quote->discount > 0)
            <div class="totals-row discount">
                <span>Desconto (–)</span>
                <span>R$ {{ number_format($quote->discount, 2, ',', '.') }}</span>
            </div>
            @endif
            <div class="totals-row total">
                <span>Valor Total</span>
                <span class="amount">R$ {{ number_format($quote->total_amount, 2, ',', '.') }}</span>
            </div>
        </div>

        {{-- Observações --}}
        @if($quote->notes)
        <p class="section-title">Termos &amp; Observações</p>
        <div class="notes-box">{{ $quote->notes }}</div>
        @endif

        {{-- CTA --}}
        <div class="cta-box">
            <p class="cta-text">
                O PDF da proposta está anexado a este e-mail para facilitar sua análise.<br>
                Em caso de dúvidas, entre em contato conosco.
            </p>
            <p style="font-size:13px;color:#374151;font-weight:600;">
                {{ $setting?->email ?? $quote->user->email }}
                @if($setting?->phone)
                    &nbsp;·&nbsp; {{ $setting->phone }}
                @endif
            </p>
        </div>

    </div>{{-- /body --}}

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        <p>Este e-mail foi enviado por <strong>{{ $companyName }}</strong></p>
        <p>{{ $setting?->address }}</p>
        <p style="margin-top:8px;">
            <a href="{{ config('app.url') }}">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</a>
        </p>
    </div>

</div>
</body>
</html>
