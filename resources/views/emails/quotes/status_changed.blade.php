<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização de Orçamento - {{ $quote->quote_number }}</title>
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
            background: {{ $status === 'approved' ? '#10b981' : '#ef4444' }};
            padding: 40px 40px 32px;
            text-align: center;
        }
        .header-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
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
            color: #4b5563;
            margin-bottom: 20px;
        }
        .detail-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 28px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .detail-row label {
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            margin-right: 15px;
        }
        .detail-row span {
            color: #111827;
            font-weight: 600;
            text-align: right;
        }
        .reason-box {
            background: #fef2f2;
            border-left: 3px solid #ef4444;
            padding: 14px 18px;
            border-radius: 0 8px 8px 0;
            margin: 15px 0 25px;
            font-size: 14px;
            color: #991b1b;
            font-style: italic;
        }
        .cta-btn {
            display: inline-block;
            background: #4f46e5;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
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
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="header-title">
            {{ $status === 'approved' ? 'Orçamento Aprovado! 🎉' : 'Orçamento Recusado 😔' }}
        </div>
    </div>
    <div class="body">
        <p class="greeting">Olá, {{ $quote->user->name }}!</p>
        <p class="intro-text">
            Seu cliente <strong>{{ $quote->client->name }}</strong> respondeu ao orçamento enviado.
        </p>

        <div class="detail-box">
            <div class="detail-row">
                <label>Número do Orçamento</label>
                <span>{{ $quote->quote_number }}</span>
            </div>
            <div class="detail-row">
                <label>Cliente</label>
                <span>{{ $quote->client->name }}</span>
            </div>
            <div class="detail-row">
                <label>Valor Total</label>
                <span>R$ {{ number_format($quote->total_amount, 2, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <label>Novo Status</label>
                <span style="color: {{ $status === 'approved' ? '#10b981' : '#ef4444' }};">
                    {{ $status === 'approved' ? 'Aprovado' : 'Recusado' }}
                </span>
            </div>
        </div>

        @if($status === 'declined' && $reason)
            <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #9ca3af; margin-bottom: 5px;">Motivo informado pelo cliente:</p>
            <div class="reason-box">
                "{{ $reason }}"
            </div>
        @endif

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ route('quotes.show', $quote) }}" class="cta-btn" style="color: #ffffff;">Visualizar no Sistema</a>
        </div>
    </div>
    <div class="footer">
        <p>Este e-mail foi gerado automaticamente pelo seu {{ config('app.name', 'Gerador de Orçamentos') }}.</p>
    </div>
</div>
</body>
</html>
