<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo - {{ $quote->quote_number }}</title>
    <style>
        @page { margin: 80px 70px; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000;
            font-size: 15px;
            line-height: 2.0;
        }

        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 4px;
            margin-bottom: 60px;
            text-transform: uppercase;
        }

        .declaration {
            text-align: justify;
            margin-bottom: 50px;
        }

        .declaration strong {
            font-weight: bold;
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
            margin-top: 100px;
            width: 400px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            padding-top: 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="title">RECIBO</div>

    @php
        $companyName = $setting->company_name ?? config('app.name', 'Gerador de Orçamentos');
        $companyDoc = $setting->document ? "inscrito(a) no CNPJ/CPF sob o nº {$setting->document}" : "";
        
        $clientName = $quote->client->name;
        $clientDoc = $quote->client->document ? "inscrito(a) no CPF/CNPJ sob o nº {$quote->client->document}" : "";
    @endphp

    <div class="declaration">
        <strong>{{ mb_strtoupper($companyName) }}</strong>, {{ $companyDoc }}, declara, para os devidos fins, que recebeu de <strong>{{ mb_strtoupper($clientName) }}</strong>, {{ $clientDoc }}, a importância de <strong>{{ $valorExtenso }}</strong> (R$ {{ number_format($quote->total_amount, 2, ',', '.') }}), referente ao orçamento <strong>{{ $quote->quote_number }}</strong>.
    </div>

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
