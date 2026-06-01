<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo - {{ $quote->quote_number }}</title>
    <style>
        @page { margin: 60px 50px; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000;
            font-size: 13px;
            line-height: 1.5;
        }

        .header-table {
            width: 100%;
            margin-bottom: 40px;
        }
        
        .header-table td {
            vertical-align: middle;
        }
        
        .header-box {
            border: 1px solid #ddd;
            padding: 10px 15px;
            width: 150px;
        }
        
        .header-box-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .header-box-value {
            font-size: 16px;
            font-weight: bold;
        }
        
        .title {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .main-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            border: 1px solid #ddd;
        }

        .main-grid td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        .main-grid tr:last-child td {
            border-bottom: none;
        }

        .col-label {
            width: 20%;
            font-weight: bold;
            border-right: 1px solid #ddd;
        }

        .col-value {
            width: 80%;
        }

        /* Itens Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 50px;
            border: 1px solid #ddd;
        }

        .items-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        .items-table td {
            padding: 12px 15px;
            font-size: 13px;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .items-table th:last-child, .items-table td:last-child {
            border-right: none;
        }

        .date-section {
            margin-top: 40px;
            font-size: 14px;
        }

        .signature-wrapper {
            margin-top: 80px;
            width: 400px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    @php
        $companyName = $setting->company_name ?? config('app.name', 'Gerador de Orçamentos');
    @endphp

    <table class="header-table">
        <tr>
            <td style="width: 30%;">
                <div class="header-box">
                    <div class="header-box-label">Orçamento</div>
                    <div class="header-box-value">{{ $quote->quote_number }}</div>
                </div>
            </td>
            <td style="width: 40%;">
                <div class="title">RECIBO</div>
            </td>
            <td style="width: 30%; text-align: right;">
                <div class="header-box" style="float: right; text-align: right;">
                    <div class="header-box-label">Valor</div>
                    <div class="header-box-value">R$ {{ number_format($quote->total_amount, 2, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="main-grid">
        <tr>
            <td class="col-label">Recebedor</td>
            <td class="col-value">
                {{ mb_strtoupper($companyName) }}<br>
                @if($setting && $setting->document)CNPJ/CPF {{ $setting->document }}@endif
            </td>
        </tr>
        <tr>
            <td class="col-label">Pagador</td>
            <td class="col-value">
                {{ mb_strtoupper($quote->client->name) }}<br>
                @if($quote->client->document)CPF/CNPJ {{ $quote->client->document }}@endif
            </td>
        </tr>
        <tr>
            <td class="col-label">Valor</td>
            <td class="col-value">
                R$ {{ number_format($quote->total_amount, 2, ',', '.') }}<br>
                {{ $valorExtenso }}
            </td>
        </tr>
        <tr>
            <td class="col-label">Referente</td>
            <td class="col-value">
                ORÇAMENTO {{ $quote->quote_number }}
            </td>
        </tr>
    </table>

    @if($quote->items->count() > 0)
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
    @endif

    @php
        \Carbon\Carbon::setLocale('pt_BR');
        $dateStr = \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y');
        
        $city = "Local";
        if ($setting && $setting->address) {
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
