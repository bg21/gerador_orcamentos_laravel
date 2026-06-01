<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orçamento {{ $quote->quote_number }}</title>
    @php
        $setting = $quote->user->companySetting;
        $primaryColor = $setting->primary_color ?? '#111827';
        $secondaryColor = $setting->secondary_color ?? '#374151';
        $companyName = $setting->company_name ?? config('app.name', 'Gerador de Orçamentos');
    @endphp
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #334155;
            font-size: 12px;
            line-height: 1.5;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }
        
        .header-bg {
            background-color: {{ $primaryColor }};
            color: #ffffff;
            padding: 50px 50px 30px 50px;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header-table td {
            vertical-align: top;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        
        .quote-title-box {
            text-align: right;
        }
        
        .quote-title {
            font-size: 36px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #ffffff;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .content-wrapper {
            padding: 40px 50px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        
        .info-table td {
            vertical-align: top;
            width: 50%;
        }
        
        .box-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: {{ $primaryColor }};
            letter-spacing: 1px;
            margin-bottom: 10px;
            border-bottom: 2px solid {{ $primaryColor }};
            padding-bottom: 5px;
            display: inline-block;
        }
        
        .client-name {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 5px;
        }
        
        .client-details {
            font-size: 11px;
            color: #475569;
            line-height: 1.6;
        }
        
        .meta-details {
            text-align: right;
        }
        
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 0;
            font-size: 11px;
        }
        .meta-table .m-label {
            color: #64748b;
            font-weight: bold;
        }
        .meta-table .m-val {
            color: #0f172a;
            text-align: right;
            font-weight: bold;
        }

        .items-wrapper {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 2px;
            margin-bottom: 30px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table th {
            background-color: {{ $primaryColor }};
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 15px;
            text-align: left;
        }
        
        .items-table td {
            padding: 12px 15px;
            font-size: 12px;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .totals-container {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        
        .totals-container td {
            vertical-align: top;
        }
        
        .notes-box {
            padding-right: 40px;
        }
        .notes-content {
            background-color: #f8fafc;
            border-left: 4px solid {{ $primaryColor }};
            padding: 15px;
            font-size: 11px;
            color: #475569;
            line-height: 1.6;
        }
        
        .totals-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            width: 300px;
            float: right;
            border-collapse: collapse;
        }
        
        .totals-box td {
            padding: 12px 20px;
            font-size: 12px;
            color: #475569;
        }
        
        .totals-row td {
            border-bottom: 1px solid #e2e8f0;
        }
        
        .t-value {
            text-align: right;
            font-weight: bold;
            color: #0f172a;
        }
        
        .totals-final td {
            background-color: {{ $primaryColor }};
            color: #ffffff;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: bold;
            border-bottom: none;
        }
        
        .totals-final .t-value {
            color: #ffffff;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f1f5f9;
            padding: 20px 50px;
            font-size: 10px;
            color: #64748b;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .signature-area {
            text-align: center;
            width: 250px;
            margin: 0 auto;
            margin-top: 50px;
        }
        .signature-img {
            max-height: 60px;
            max-width: 200px;
            margin-bottom: 10px;
        }
        .signature-line {
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            font-size: 11px;
            color: #475569;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header-bg">
        <table class="header-table">
            <tr>
                <td style="width: 50%;">
                    @if($setting && $setting->logo_path)
                        <img src="{{ storage_path('app/public/' . $setting->logo_path) }}" style="max-height: 70px; max-width: 200px; object-fit: contain; margin-bottom: 15px; background: white; padding: 5px; border-radius: 4px;" />
                    @else
                        <div class="company-name">{{ $companyName }}</div>
                    @endif
                    <div style="font-size: 11px; opacity: 0.8; line-height: 1.6;">
                        @if($setting && $setting->document)CNPJ/CPF: {{ $setting->document }}<br>@endif
                        @if($setting && $setting->phone)Tel: {{ $setting->phone }}<br>@endif
                        Email: {{ $setting->email ?? $quote->user->email }}<br>
                        @if($setting && $setting->address){{ $setting->address }}@endif
                    </div>
                </td>
                <td style="width: 50%;" class="quote-title-box">
                    <div class="quote-title">Proposta</div>
                    <div style="font-size: 14px; opacity: 0.9; font-weight: bold;">#{{ $quote->quote_number }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content-wrapper">
        <table class="info-table">
            <tr>
                <td style="padding-right: 30px;">
                    <div class="box-title">Orçamento Para</div>
                    <div class="client-name">{{ $quote->client->name }}</div>
                    <div class="client-details">
                        @if($quote->client->document)CPF/CNPJ: {{ $quote->client->document }}<br>@endif
                        @if($quote->client->phone)Tel: {{ $quote->client->phone }}<br>@endif
                        Email: {{ $quote->client->email }}<br>
                        @if($quote->client->address){{ $quote->client->address }}@endif
                    </div>
                </td>
                <td class="meta-details">
                    <div class="box-title" style="float: right;">Detalhes</div>
                    <div style="clear:both;"></div>
                    <table class="meta-table" style="width: 200px; float: right;">
                        <tr>
                            <td class="m-label">Data Emissão:</td>
                            <td class="m-val">{{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}</td>
                        </tr>
                        @if($quote->due_date)
                        <tr>
                            <td class="m-label">Validade:</td>
                            <td class="m-val">{{ \Carbon\Carbon::parse($quote->due_date)->format('d/m/Y') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="m-label">Status:</td>
                            <td class="m-val">
                                @if($quote->status === 'draft') Rascunho
                                @elseif($quote->status === 'sent') Enviado
                                @elseif($quote->status === 'approved') Aprovado
                                @else Recusado @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="items-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 45%;">Descrição do Serviço/Produto</th>
                        <th style="width: 15%; text-align: center;">Quantidade</th>
                        <th style="width: 20%; text-align: right;">Valor Unitário</th>
                        <th style="width: 20%; text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotal = 0; @endphp
                    @foreach ($quote->items as $item)
                        @php $subtotal += $item->total_price; @endphp
                        <tr>
                            <td style="font-weight: bold;">{{ $item->description }}</td>
                            <td style="text-align: center; color: #64748b;">{{ $item->quantity }}</td>
                            <td style="text-align: right; color: #64748b;">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                            <td style="text-align: right; font-weight: bold; color: #0f172a;">R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <table class="totals-container">
            <tr>
                <td style="width: 55%;" class="notes-box">
                    @if($quote->notes)
                        <div class="box-title">Observações e Condições</div>
                        <div class="notes-content">
                            {!! nl2br(e($quote->notes)) !!}
                        </div>
                    @endif
                </td>
                <td style="width: 45%;">
                    <table class="totals-box">
                        <tr class="totals-row">
                            <td>Subtotal</td>
                            <td class="t-value">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                        </tr>
                        @if($quote->discount > 0)
                        <tr class="totals-row">
                            <td>Desconto</td>
                            <td class="t-value" style="color: #ef4444;">- R$ {{ number_format($quote->discount, 2, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="totals-final">
                            <td>Total Final</td>
                            <td class="t-value">R$ {{ number_format($quote->total_amount, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @if($setting && ($setting->signature_path || $setting->signature_text))
            <div class="signature-area">
                @if($setting->signature_path)
                    <img src="{{ storage_path('app/public/' . $setting->signature_path) }}" class="signature-img" />
                @endif
                <div class="signature-line">
                    {!! nl2br(e($setting->signature_text ?? 'Assinatura do Prestador')) !!}
                </div>
            </div>
        @endif
    </div>

    <div class="footer">
        <strong>{{ $companyName }}</strong> &copy; {{ date('Y') }} - Todos os direitos reservados.<br>
        Documento gerado eletronicamente.
    </div>

</body>
</html>
