<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo - Orçamento {{ $quote->quote_number }}</title>
    @php
        $companyName = $setting->company_name ?? config('app.name', 'Gerador de Orçamentos');
        $primaryColor = $setting->primary_color ?? '#111827';
    @endphp
    <style>
        @page { margin: 60px 50px; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111827;
            font-size: 13px;
            line-height: 1.5;
            background: #ffffff;
        }

        .title {
            text-align: center;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 4px;
            margin-bottom: 40px;
            text-transform: uppercase;
        }

        .header-box {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header-table td {
            vertical-align: middle;
        }

        .logo-cell {
            width: 120px;
            text-align: center;
            padding-right: 15px;
            border-right: 1px solid #e2e8f0;
        }

        .logo-img {
            max-height: 60px;
            max-width: 100px;
            object-fit: contain;
        }

        .company-info {
            padding-left: 20px;
            font-size: 11px;
            color: #475569;
            line-height: 1.6;
        }
        .company-info strong {
            font-size: 14px;
            color: #111827;
        }

        .contact-info {
            text-align: right;
            font-size: 11px;
            color: #475569;
            line-height: 1.6;
        }

        /* Tabela Principal */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .main-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .main-table tr:last-child td {
            border-bottom: none;
        }

        .col-label {
            width: 15%;
            font-weight: bold;
            color: #111827;
            background-color: #f8fafc;
            border-right: 1px solid #e2e8f0;
        }

        .col-value {
            color: #111827;
        }

        /* Itens Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            border: 1px solid #e2e8f0;
        }

        .items-table th {
            background-color: #f8fafc;
            padding: 10px 15px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table td {
            padding: 10px 15px;
            font-size: 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .date-section {
            margin-top: 40px;
            font-size: 14px;
            color: #111827;
        }

        .signature-wrapper {
            margin-top: 80px;
            width: 350px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            font-size: 12px;
            color: #111827;
        }
    </style>
</head>
<body>

    <div class="title">RECIBO</div>

    <div class="header-box">
        <table class="header-table">
            <tr>
                @if($setting && $setting->logo_path)
                <td class="logo-cell">
                    <img src="{{ storage_path('app/public/' . $setting->logo_path) }}" class="logo-img" />
                </td>
                @endif
                <td>
                    <div class="company-info">
                        <strong>{{ $companyName }}</strong><br>
                        @if($setting && $setting->document)CNPJ/CPF: {{ $setting->document }}<br>@endif
                        @if($setting && $setting->address){{ $setting->address }}@endif
                    </div>
                </td>
                <td>
                    <div class="contact-info">
                        @if($setting && $setting->phone){{ $setting->phone }}<br>@endif
                        {{ $setting->email ?? $quote->user->email }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <tr>
            <td class="col-label">Cliente</td>
            <td class="col-value">
                <div style="font-weight: bold;">{{ $quote->client->name }}</div>
                @if($quote->client->document)
                    <div style="font-size: 12px; margin-top: 3px; color: #475569;">CPF/CNPJ: {{ $quote->client->document }}</div>
                @endif
            </td>
        </tr>
        <tr>
            <td class="col-label">Valor</td>
            <td class="col-value">
                <div>R$ {{ number_format($quote->total_amount, 2, ',', '.') }}</div>
                <div style="font-size: 11px; font-weight: bold; margin-top: 4px;">{{ $valorExtenso }}</div>
            </td>
        </tr>
        <tr>
            <td class="col-label">Referente</td>
            <td class="col-value">
                ORÇAMENTO {{ $quote->quote_number }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 85%;">Item</th>
                <th style="width: 15%; text-align: center;">Qtd.</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quote->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
        \Carbon\Carbon::setLocale('pt_BR');
        $dateStr = \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y');
        
        // Extract city from address if possible, fallback to standard text
        $city = "Local";
        if ($setting && $setting->address) {
            // Very naive city extraction, usually the first part of address or we just leave empty
            // Best is to just write the date. We will just output the date nicely.
            $city = explode('-', $setting->address)[0] ?? 'Brasil';
            $city = trim(explode(',', $city)[0]);
        } else {
            $city = '';
        }
    @endphp

    <div class="date-section">
        {{ $city ? $city . ', ' : '' }}{{ $dateStr }}.
    </div>

    <div class="signature-wrapper">
        <div class="signature-line">
            {{ $companyName }}
        </div>
    </div>

</body>
</html>
