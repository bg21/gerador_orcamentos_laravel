<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orçamento {{ $quote->quote_number }}</title>
    @php
        $setting = $quote->user->companySetting;
        $primaryColor = !empty($setting->primary_color) ? $setting->primary_color : '#2563eb';
        $secondaryColor = !empty($setting->secondary_color) ? $setting->secondary_color : '#1e3a8a';
        $companyName = $setting->company_name ?? config('app.name', 'Gerador de Orçamentos');
    @endphp
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.5;
            background: #fff;
            margin: 40px 50px;
        }

        /* ── Header ── */
        .header-bar {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header-bar td { vertical-align: middle; }
        .company-info { font-size: 10px; color: #475569; line-height: 1.6; }
        .company-info strong { font-size: 12px; color: #0f172a; }
        .contact-info { text-align: right; font-size: 10px; color: #475569; line-height: 1.6; }

        /* ── Quote metadata row ── */
        .meta-row {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8fafc;
            margin-bottom: 14px;
        }
        .meta-row td {
            padding: 7px 12px;
            font-size: 10px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        .meta-row .meta-label { font-weight: bold; color: #475569; display: block; }
        .meta-row .meta-value { color: #0f172a; font-weight: bold; }

        /* ── Section heading ── */
        .section-heading {
            color: #fff;
            font-weight: bold;
            font-size: 10px;
            padding: 5px 10px;
            text-align: center;
            margin-bottom: 0;
        }

        /* ── Client table ── */
        .client-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .client-table td {
            border: 1px solid #e2e8f0;
            padding: 5px 10px;
            font-size: 10px;
            vertical-align: top;
        }
        .client-table td.label-cell {
            font-weight: bold;
            color: #334155;
            width: 22%;
            background-color: #f8fafc;
        }

        /* ── Items table ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .items-table th {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            font-size: 9px;
            font-weight: bold;
            text-align: left;
            color: #475569;
        }
        .items-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            font-size: 10px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* ── Summary bar ── */
        .summary-bar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .summary-bar td {
            border: 1px solid #e2e8f0;
            padding: 6px 10px;
            font-size: 10px;
        }
        .summary-bar .total-cell {
            font-weight: bold;
            font-size: 11px;
        }

        /* ── Notes ── */
        .notes-box {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            font-size: 10px;
            color: #475569;
            margin-bottom: 14px;
            background-color: #f8fafc;
        }

        /* ── Signature ── */
        .signature-row {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .signature-row td { vertical-align: bottom; font-size: 9px; color: #64748b; }
        .signature-line { border-top: 1px solid #cbd5e1; padding-top: 4px; text-align: center; }

        /* ── Footer ── */
        .footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            padding-top: 6px;
        }
        .footer-inner {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-inner td { font-size: 9px; color: #94a3b8; padding: 0 4px; }
    </style>
    <style>.section-heading { background-color: <?= $primaryColor ?>; } .total-cell { color: <?= $primaryColor ?>; }</style>
</head>
<body>

    <!-- Header -->
    <table class="header-bar">
        <tr>
            <td style="width: 60%;">
                @if($setting && $setting->logo_path)
                    <img src="{{ storage_path('app/public/' . $setting->logo_path) }}" style="max-height: 40px; max-width: 140px; object-fit: contain; display: block; margin-bottom: 4px;" />
                @endif
                <div class="company-info">
                    <strong>{{ $companyName }}</strong><br>
                    @if($setting && $setting->document)CNPJ {{ $setting->document }}<br>@endif
                    @if($setting && $setting->address){{ $setting->address }}@endif
                </div>
            </td>
            <td>
                <div class="contact-info">
                    @if($setting && $setting->phone){{ $setting->phone }}<br>@endif
                    {{ $setting->email ?? $quote->user->email }}<br>
                    @if($setting && $setting->website){{ $setting->website }}@endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Meta row: Orçamento / Data / Validade -->
    <table class="meta-row">
        <tr>
            <td style="width: 33%;">
                <span class="meta-label">Orçamento</span>
                <span class="meta-value">{{ $quote->quote_number }}</span>
            </td>
            <td style="width: 33%;">
                <span class="meta-label">Data</span>
                <span class="meta-value">{{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}</span>
            </td>
            <td style="width: 34%;">
                <span class="meta-label">Validade</span>
                <span class="meta-value">{{ $quote->due_date ? \Carbon\Carbon::parse($quote->due_date)->format('d/m/Y') : '—' }}</span>
            </td>
        </tr>
    </table>

    <!-- Client Section -->
    <div class="section-heading">Dados do cliente</div>
    <table class="client-table">
        <tr>
            <td class="label-cell">Nome</td>
            <td colspan="3">{{ $quote->client->name }}</td>
        </tr>
        <tr>
            <td class="label-cell">Telefone</td>
            <td>{{ $quote->client->phone ?? '—' }}</td>
            <td class="label-cell" style="width: 10%;">Email</td>
            <td>{{ $quote->client->email }}</td>
        </tr>
        @if($quote->client->document)
        <tr>
            <td class="label-cell">CPF / CNPJ</td>
            <td colspan="3">{{ $quote->client->document }}</td>
        </tr>
        @endif
        @if($quote->client->address)
        <tr>
            <td class="label-cell">Endereço</td>
            <td colspan="3">{{ $quote->client->address }}</td>
        </tr>
        @endif
    </table>

    <!-- Items Section -->
    <div class="section-heading">Itens</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 46%;">Nome</th>
                <th class="text-center" style="width: 10%;">Qtd.</th>
                <th class="text-right" style="width: 20%;">Valor</th>
                <th class="text-right" style="width: 20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; $i = 1; @endphp
            @foreach ($quote->items as $item)
                @php $subtotal += $item->total_price; @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary bar -->
    <table class="summary-bar">
        <tr>
            <td><strong>Subtotal:</strong> R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
            @if($quote->discount > 0)
            <td style="text-align: center;"><strong>Desconto:</strong> R$ {{ number_format($quote->discount, 2, ',', '.') }}</td>
            @else
            <td></td>
            @endif
            <td class="total-cell text-right"><strong>Total: R$ {{ number_format($quote->total_amount, 2, ',', '.') }}</strong></td>
        </tr>
    </table>

    <!-- Notes -->
    @if($quote->notes)
    <div class="section-heading">Observações</div>
    <div class="notes-box">{{ $quote->notes }}</div>
    @endif

    <!-- Signature -->
    @if($setting && ($setting->signature_path || $setting->signature_text))
    <table class="signature-row">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center;">
                @if($setting->signature_path)
                    <img src="{{ storage_path('app/public/' . $setting->signature_path) }}" style="max-height: 45px; max-width: 180px; object-fit: contain; display: block; margin: 0 auto 4px;" />
                @endif
                <div class="signature-line">
                    {!! nl2br(e($setting->signature_text ?? 'Assinatura do Prestador')) !!}
                </div>
            </td>
        </tr>
    </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <table class="footer-inner">
            <tr>
                <td style="text-align: left;">{{ $companyName }}</td>
                <td style="text-align: right;">{{ $quote->client->name }}</td>
            </tr>
        </table>
    </div>

</body>
</html>
