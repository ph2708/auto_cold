<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Orçamento {{ $serviceOrder->order_number }} - Auto Cold</title>
    <style>
        @page { size: A4; margin: 15mm; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a202c;
            background: #fff;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f59e0b;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header-title h1 {
            margin: 0;
            font-size: 20px;
            color: #0f172a;
        }
        .header-title p {
            margin: 2px 0 0 0;
            color: #64748b;
            font-size: 11px;
        }
        .budget-badge {
            text-align: right;
        }
        .budget-number {
            font-size: 18px;
            font-weight: 800;
            color: #d97706;
            font-family: monospace;
        }
        .section-box {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 12px;
        }
        .section-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #d97706;
            margin-bottom: 6px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 4px;
        }
        .grid-2 {
            display: flex;
            gap: 12px;
        }
        .grid-2 > div {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 6px;
        }
        th {
            background: #fef3c7;
            text-align: left;
            padding: 6px 8px;
            font-weight: 700;
            border-bottom: 1px solid #fde68a;
            color: #92400e;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals-box {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
        .totals-table {
            width: 260px;
        }
        .totals-table td {
            padding: 4px 8px;
        }
        .grand-total {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            border-top: 2px solid #f59e0b;
            background: #fffbeb;
        }
        .terms {
            margin-top: 25px;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 10px;
        }
        .sign-line {
            width: 45%;
            text-align: center;
            border-top: 1px solid #475569;
            font-size: 11px;
            padding-top: 4px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="background: #0f172a; color: #fff; padding: 10px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; border-radius: 8px;">
        <span>Visualização do Orçamento Prévio</span>
        <button onclick="window.print()" style="background: #f59e0b; color: #000; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            🖨️ Imprimir Orçamento / Salvar PDF
        </button>
    </div>

    <!-- Header Oficina -->
    <div class="header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <img src="{{ asset($appSettings['logo_light'] ?? 'images/logo-light.png') }}" alt="Logo" style="height: {{ min($appSettings['logo_height'] ?? 48, 55) }}px; object-contain: contain;">
            <div class="header-title">
                <h1>{{ $appSettings['company_name'] ?? 'AUTO COLD' }} - {{ $appSettings['company_subtitle'] ?? 'ELÉTRICA E AR CONDICIONADO' }}</h1>
                <p><strong>{{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}</strong> | Contato/WhatsApp: <strong>{{ $appSettings['phone'] ?? '(64) 99329-5596' }}</strong></p>
                <p style="font-style: italic; color: #d97706; font-size: 10px;">"{{ $appSettings['slogan'] ?? 'Confiança e qualidade: a combinação perfeita para o seu veículo.' }}"</p>
            </div>
        </div>
        <div class="budget-badge">
            <div class="budget-number">ORÇAMENTO: {{ $serviceOrder->order_number }}</div>
            <div style="font-size: 10px; color: #64748b;">Emitido em: {{ date('d/m/Y') }}</div>
        </div>
    </div>

    <!-- Dados do Cliente e do Veículo -->
    <div class="grid-2">
        <div class="section-box">
            <div class="section-title">Dados do Cliente</div>
            <div><strong>Nome:</strong> {{ $serviceOrder->customer?->name }}</div>
            <div><strong>Telefone/WhatsApp:</strong> {{ $serviceOrder->customer?->whatsapp ?? $serviceOrder->customer?->phone ?? '-' }}</div>
            <div><strong>Cidade/UF:</strong> {{ $serviceOrder->customer?->city ?? '-' }} / {{ $serviceOrder->customer?->state ?? '-' }}</div>
        </div>

        <div class="section-box">
            <div class="section-title">Dados do Veículo</div>
            <div><strong>Veículo:</strong> {{ $serviceOrder->vehicle?->brand }} {{ $serviceOrder->vehicle?->model }} ({{ $serviceOrder->vehicle?->year ?? 'Ano -' }})</div>
            <div><strong>Placa:</strong> <span style="font-family: monospace; font-weight: bold; color: #d97706;">{{ $serviceOrder->vehicle?->plate }}</span></div>
            <div><strong>KM Atual:</strong> {{ $serviceOrder->entry_km ? number_format($serviceOrder->entry_km, 0, ',', '.') . ' km' : '-' }}</div>
        </div>
    </div>

    <!-- Defeito / Diagnóstico Preliminar -->
    <div class="section-box">
        <div class="section-title">Reclamação do Cliente & Avaliação Prévia</div>
        <div style="margin-bottom: 4px;"><strong>Sintomas Relatados:</strong> {{ $serviceOrder->reported_defect }}</div>
        @if($serviceOrder->technical_diagnosis)
            <div><strong>Diagnóstico Proposto:</strong> {{ $serviceOrder->technical_diagnosis }}</div>
        @endif
    </div>

    <!-- Peças Orçadas -->
    @if($serviceOrder->items->count() > 0)
        <div class="section-box">
            <div class="section-title">Peças & Componentes Necessários</div>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descrição da Peça / Componente</th>
                        <th class="text-center">Qtd</th>
                        <th class="text-right">Unitário (R$)</th>
                        <th class="text-right">Total (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceOrder->items as $item)
                        <tr>
                            <td style="font-family: monospace;">{{ $item->product?->sku ?? 'COT' }}</td>
                            <td>{{ $item->item_name ?? $item->product?->name }} {{ $item->product?->brand ? '(' . $item->product->brand . ')' : '' }}</td>
                            <td class="text-center">{{ $item->quantity }} {{ $item->unit ?? $item->product?->unit ?? 'UN' }}</td>
                            <td class="text-right">{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($item->total_amount, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Mão de Obra Orçada -->
    @if($serviceOrder->services->count() > 0)
        <div class="section-box">
            <div class="section-title">Mão de Obra & Serviços Técnicos</div>
            <table>
                <thead>
                    <tr>
                        <th>Descrição do Serviço</th>
                        <th class="text-center">Qtd / Horas</th>
                        <th class="text-right">Unitário (R$)</th>
                        <th class="text-right">Total (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceOrder->services as $srv)
                        <tr>
                            <td>{{ $srv->description }}</td>
                            <td class="text-center">{{ $srv->quantity }}</td>
                            <td class="text-right">{{ number_format($srv->unit_price, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($srv->total_amount, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Totais -->
    <div class="totals-box">
        <table class="totals-table">
            <tr>
                <td>Total em Peças:</td>
                <td class="text-right">R$ {{ number_format($serviceOrder->products_total, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total em Serviços:</td>
                <td class="text-right">R$ {{ number_format($serviceOrder->services_total, 2, ',', '.') }}</td>
            </tr>
            @if($serviceOrder->discount > 0)
                <tr>
                    <td style="color: #e11d48;">Desconto Especial:</td>
                    <td class="text-right" style="color: #e11d48;">- R$ {{ number_format($serviceOrder->discount, 2, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="grand-total">
                <td>TOTAL ESTIMADO:</td>
                <td class="text-right">R$ {{ number_format($serviceOrder->total_amount, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Validade do Orçamento -->
    <div class="terms">
        <strong>Validade da Proposta:</strong> Este orçamento é válido por <strong>7 (sete) dias corridos</strong> a contar da data de emissão, estando sujeito a confirmação prévia de disponibilidade de estoque e variação de preço dos distribuidores de autopeças. O serviço será iniciado somente após a aprovação expressa do cliente.
    </div>

    <!-- Assinaturas -->
    <div class="signatures">
        <div class="sign-line">
            AUTO COLD - Consultor / Técnico
        </div>
        <div class="sign-line">
            De Acordo / Aprovado pelo Cliente
        </div>
    </div>

</body>
</html>
