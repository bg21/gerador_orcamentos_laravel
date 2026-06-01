<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orçamento {{ $quote->quote_number }}</title>
    @php
        $setting = $quote->user->companySetting;
        $primaryColor = $setting->primary_color ?? '#000000';
        $secondaryColor = $setting->secondary_color ?? '#666666';
        $companyName = $setting->company_name ?? config('app.name', 'Gerador de Orçamentos');
    @endphp
    <style>
        @page { margin: 60px 50px; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111827;
            font-size: 11px;
            line-height: 1.5;
            background: #ffffff;
        }
        
        .header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 30px;
            margin-bottom: 40px;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header-table td {
            vertical-align: top;
        }
        
        .title-huge {
            font-size: 42px;
            font-weight: 300;
            letter-spacing: -1px;
            color: #111827;
            margin-bottom: 5px;
            line-height: 1;
        }
        
        .quote-id {
            font-size: 14px;
            font-weight: bold;
            color: {{ $primaryColor }};
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .logo-img {
            max-height: 60px;
            max-width: 180px;
            object-fit: contain;
            float: right;
        }
        
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 50px;
        }
        
        .grid-table td {
            width: 33.33%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .grid-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .grid-value {
            font-size: 12px;
            color: #111827;
            line-height: 1.6;
        }
        .grid-value strong {
            font-size: 14px;
        }
        
        .items-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #111827;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th {
            border-top: 1px solid #111827;
            border-bottom: 1px solid #111827;
            padding: 12px 0;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: left;
            color: #374151;
        }
        
        .items-table td {
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
            vertical-align: top;
        }
        
        .items-table .td-desc {
            font-weight: bold;
            color: #111827;
            font-size: 13px;
        }
        
        .totals-table {
            width: 350px;
            float: right;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        
        .totals-table td {
            padding: 10px 0;
            font-size: 13px;
        }
        
        .totals-table .col-label {
            text-align: right;
            padding-right: 30px;
            color: #6b7280;
        }
        
        .totals-table .col-value {
            text-align: right;
            font-weight: bold;
            color: #111827;
        }
        
        .totals-table .row-total td {
            border-top: 2px solid #111827;
            padding-top: 15px;
            font-size: 18px;
            color: {{ $primaryColor }};
        }
        
        .notes-section {
            clear: both;
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
        }
        
        .signature-wrapper {
            margin-top: 50px;
            width: 250px;
        }
        .signature-line {
            border-top: 1px solid #111827;
            padding-top: 8px;
            font-size: 11px;
            font-weight: bold;
            color: #374151;
        }
    </style>
</head>
<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="title-huge">PROPOSTA</div>
                    <div class="quote-id">#{{ $quote->quote_number }}</div>
                </td>
                <td>
                    @if($setting && $setting->logo_path)
                        <img src="{{ storage_path('app/public/' . $setting->logo_path) }}" class="logo-img" />
                    @else
                        <div style="font-size: 20px; font-weight: bold; text-align: right;">{{ $companyName }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table class="grid-table">
        <tr>
            <td>
                <div class="grid-label">Para</div>
                <div class="grid-value">
                    <strong>{{ $quote->client->name }}</strong><br>
                    @if($quote->client->document)CPF/CNPJ: {{ $quote->client->document }}<br>@endif
                    @if($quote->client->phone){{ $quote->client->phone }}<br>@endif
                    {{ $quote->client->email }}<br>
                    @if($quote->client->address){{ $quote->client->address }}@endif
                </div>
            </td>
            <td>
                <div class="grid-label">De</div>
                <div class="grid-value">
                    <strong>{{ $setting->company_name ?? $quote->user->name }}</strong><br>
                    @if($setting && $setting->document)CNPJ: {{ $setting->document }}<br>@endif
                    @if($setting && $setting->phone){{ $setting->phone }}<br>@endif
                    {{ $setting->email ?? $quote->user->email }}<br>
                    @if($setting && $setting->address){{ $setting->address }}@endif
                </div>
            </td>
            <td>
                <div class="grid-label">Detalhes</div>
                <div class="grid-value">
                    <strong>Data:</strong> {{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}<br>
                    @if($quote->due_date)
                        <strong>Válido até:</strong> {{ \Carbon\Carbon::parse($quote->due_date)->format('d/m/Y') }}<br>
                    @endif
                    <strong>Status:</strong> 
                    @if($quote->status === 'draft') Rascunho
                    @elseif($quote->status === 'sent') Enviado
                    @elseif($quote->status === 'approved') Aprovado
                    @else Recusado @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="items-title">Serviços / Produtos</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Descrição</th>
                <th style="width: 15%; text-align: center;">Qtd</th>
                <th style="width: 15%; text-align: right;">Valor Un.</th>
                <th style="width: 20%; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach ($quote->items as $item)
                @php $subtotal += $item->total_price; @endphp
                <tr>
                    <td class="td-desc">{{ $item->description }}</td>
                    <td style="text-align: center; color: #6b7280;">{{ $item->quantity }}</td>
                    <td style="text-align: right; color: #6b7280;">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold;">R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td class="col-label">Subtotal</td>
            <td class="col-value">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
        </tr>
        @if($quote->discount > 0)
        <tr>
            <td class="col-label">Desconto</td>
            <td class="col-value" style="color: #ef4444;">- R$ {{ number_format($quote->discount, 2, ',', '.') }}</td>
        </tr>
        @endif
        <tr class="row-total">
            <td class="col-label" style="color: #111827;">Total Final</td>
            <td class="col-value">R$ {{ number_format($quote->total_amount, 2, ',', '.') }}</td>
        </tr>
    </table>

    @if($quote->notes)
    <div class="notes-section">
        <div class="grid-label">Observações e Condições</div>
        <div style="font-size: 11px; color: #4b5563; line-height: 1.6; white-space: pre-wrap;">{{ $quote->notes }}</div>
    </div>
    @endif

    @if($setting && ($setting->signature_path || $setting->signature_text))
    <div class="signature-wrapper">
        @if($setting->signature_path)
            <img src="{{ storage_path('app/public/' . $setting->signature_path) }}" style="max-height: 50px; max-width: 200px; object-fit: contain; margin-bottom: 10px;" />
        @endif
        <div class="signature-line">
            {!! nl2br(e($setting->signature_text ?? 'Assinatura do Prestador')) !!}
        </div>
    </div>
    @endif

</body>
</html>
