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
            color: #334155;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .header-table td {
            vertical-align: top;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: {{ $primaryColor }};
        }
        .brand-subtitle {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 3px;
            font-weight: bold;
        }
        .quote-number {
            font-size: 22px;
            font-weight: bold;
            text-align: right;
            color: #0f172a;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 10px;
            text-transform: uppercase;
            margin-top: 5px;
            text-align: right;
        }
        .status-draft { background-color: #f1f5f9; color: #475569; }
        .status-sent { background-color: #dbeafe; color: #1d4ed8; }
        .status-approved { background-color: #dcfce7; color: #15803d; }
        .status-declined { background-color: #fee2e2; color: #b91c1c; }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table td {
            width: 50%;
            vertical-align: top;
        }
        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            border-bottom: 1px solid {{ $primaryColor }};
            padding-bottom: 3px;
        }
        .details-name {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 4px;
        }
        .details-info {
            font-size: 11px;
            color: #475569;
            margin-top: 3px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 8px 10px;
            border-bottom: 2px solid {{ $primaryColor }};
            text-align: left;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        
        .notes-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            font-size: 10px;
            color: #475569;
        }
        
        .summary-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
        }
        .summary-box td {
            border: none;
            padding: 4px 0;
            font-size: 11px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <table class="header-table">
        <tr>
            <td>
                @if($setting && $setting->logo_path)
                    <img src="{{ storage_path('app/public/' . $setting->logo_path) }}" style="max-height: 45px; max-width: 160px; object-fit: contain;" />
                @else
                    <div class="brand-title">{{ $companyName }}</div>
                @endif
                <div class="brand-subtitle">Proposta Comercial</div>
                <div style="margin-top: 10px; font-size: 11px; color: #475569; line-height: 1.4;">
                    <strong>Emitido por:</strong> {{ $setting->company_name ?? $quote->user->name }}<br>
                    @if($setting && $setting->document)
                        <strong>CNPJ/CPF:</strong> {{ $setting->document }}<br>
                    @endif
                    <strong>Contato:</strong> {{ $setting->email ?? $quote->user->email }}
                    @if($setting && $setting->phone)
                        | <strong>Tel:</strong> {{ $setting->phone }}
                    @endif
                    @if($setting && $setting->address)
                        <br><strong>Endereço:</strong> {{ $setting->address }}
                    @endif
                </div>
            </td>
            <td style="text-align: right;">
                <div class="quote-number">{{ $quote->quote_number }}</div>
                <div>
                    @if ($quote->status === 'draft')
                        <span class="status-badge status-draft">Rascunho</span>
                    @elseif ($quote->status === 'sent')
                        <span class="status-badge status-sent">Enviado</span>
                    @elseif ($quote->status === 'approved')
                        <span class="status-badge status-approved">Aprovado</span>
                    @else
                        <span class="status-badge status-declined">Recusado</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Dates and Client details -->
    <table class="details-table">
        <tr>
            <td>
                <div class="section-title">Orçado Para</div>
                <div class="details-name">{{ $quote->client->name }}</div>
                <div class="details-info">
                    @if($quote->client->document)
                        <strong>CPF/CNPJ:</strong> {{ $quote->client->document }}<br>
                    @endif
                    <strong>E-mail:</strong> {{ $quote->client->email }}<br>
                    @if($quote->client->phone)
                        <strong>Telefone:</strong> {{ $quote->client->phone }}<br>
                    @endif
                    @if($quote->client->address)
                        <strong>Endereço:</strong> {{ $quote->client->address }}
                    @endif
                </div>
            </td>
            <td style="text-align: right; padding-right: 0;">
                <div style="display: inline-block; text-align: right;">
                    <div class="section-title" style="text-align: right;">Datas de Controle</div>
                    <div class="details-info">
                        <strong>Data de Emissão:</strong> {{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}<br>
                        @if($quote->due_date)
                            <strong>Válido Até:</strong> {{ \Carbon\Carbon::parse($quote->due_date)->format('d/m/Y') }}
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Specification Table -->
    <div class="section-title">Descrição dos Serviços</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Serviço / Descrição</th>
                <th class="text-center" style="width: 10%;">Qtd</th>
                <th class="text-right" style="width: 20%;">Preço Unitário</th>
                <th class="text-right" style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
            @endphp
            @foreach ($quote->items as $item)
                @php
                    $subtotal += $item->total_price;
                @endphp
                <tr>
                    <td style="font-weight: bold; color: #0f172a;">{{ $item->description }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold; color: #0f172a;">R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Financial totals & Notes -->
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 55%; vertical-align: top; padding-right: 20px;">
                @if($quote->notes)
                    <div class="section-title">Observações e Termos</div>
                    <div class="notes-box" style="white-space: pre-wrap;">{{ $quote->notes }}</div>
                @endif
            </td>
            <td style="width: 45%; vertical-align: top;">
                <table class="summary-box" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="color: #64748b;">Subtotal</td>
                        <td class="text-right" style="font-weight: bold;">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                    </tr>
                    @if($quote->discount > 0)
                        <tr>
                            <td style="color: #16a34a;">Desconto (-)</td>
                            <td class="text-right" style="font-weight: bold; color: #16a34a;">R$ {{ number_format($quote->discount, 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr style="border-top: 1px solid #cbd5e1;">
                        <td style="padding-top: 6px; font-weight: bold; font-size: 13px; color: {{ $primaryColor }};">Valor Total</td>
                        <td class="text-right" style="padding-top: 6px; font-weight: bold; font-size: 14px; color: {{ $primaryColor }};">R$ {{ number_format($quote->total_amount, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Signature block -->
    @if($setting && ($setting->signature_path || $setting->signature_text))
        <table style="width: 100%; margin-top: 40px; border-collapse: collapse;">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <div style="width: 220px; margin-left: auto; text-align: center;">
                        @if($setting->signature_path)
                            <img src="{{ storage_path('app/public/' . $setting->signature_path) }}" style="max-height: 50px; max-width: 200px; object-fit: contain; margin-bottom: 5px;" />
                        @endif
                        <div style="border-top: 1px solid #cbd5e1; padding-top: 5px; font-size: 10px; color: #475569; font-weight: bold; line-height: 1.4;">
                            {!! nl2br(e($setting->signature_text ?? 'Assinatura do Prestador')) !!}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    @endif

    <!-- Footer static strip -->
    <div class="footer">
        Este documento é uma proposta comercial gerada eletronicamente. Obrigado pela parceria!
    </div>
</body>
</html>
