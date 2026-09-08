<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Espelho da Ordem de Serviço {{ $serviceOrder->order_number }} - Auto Cold</title>
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
            border-bottom: 2px solid #0284c7;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header-title h1 {
            margin: 0;
            font-size: 20px;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .header-title p {
            margin: 2px 0 0 0;
            color: #64748b;
            font-size: 11px;
        }
        .os-badge {
            text-align: right;
        }
        .os-number {
            font-size: 18px;
            font-weight: 800;
            color: #0284c7;
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
            color: #0369a1;
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
            background: #f1f5f9;
            text-align: left;
            padding: 6px 8px;
            font-weight: 700;
            border-bottom: 1px solid #cbd5e1;
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
            border-top: 2px solid #0284c7;
            background: #f0f9ff;
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
        <span>Visualização para Impressão da OS</span>
        <button onclick="window.print()" style="background: #0ea5e9; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            🖨️ Imprimir / Salvar PDF
        </button>
    </div>

    <!-- Header Oficina -->
    <div class="header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <img src="{{ asset($appSettings['logo_light'] ?? 'images/logo-light.png') }}" alt="Logo" style="height: {{ min($appSettings['logo_height'] ?? 48, 55) }}px; object-contain: contain;">
            <div class="header-title">
                <h1>{{ $appSettings['company_name'] ?? 'AUTO COLD' }} - {{ $appSettings['company_subtitle'] ?? 'ELÉTRICA E AR CONDICIONADO' }}</h1>
                <p><strong>{{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}</strong> | Contato/WhatsApp: <strong>{{ $appSettings['phone'] ?? '(64) 99329-5596' }}</strong></p>
                <p style="font-style: italic; color: #0284c7; font-size: 10px;">"{{ $appSettings['slogan'] ?? 'Confiança e qualidade: a combinação perfeita para o seu veículo.' }}"</p>
            </div>
        </div>
        <div class="os-badge">
            <div class="os-number">{{ $serviceOrder->order_number }}</div>
            <div style="font-size: 10px; color: #64748b;">Status: {{ strtoupper($serviceOrder->status_label) }}</div>
        </div>
    </div>

    <!-- Dados do Cliente e do Veículo -->
    <div class="grid-2">
        <div class="section-box">
            <div class="section-title">Dados do Cliente</div>
            <div><strong>Nome:</strong> {{ $serviceOrder->customer?->name }}</div>
            <div><strong>Telefone/WhatsApp:</strong> {{ $serviceOrder->customer?->whatsapp ?? $serviceOrder->customer?->phone ?? '-' }}</div>
            <div><strong>CPF/CNPJ:</strong> {{ $serviceOrder->customer?->document_number ?? '-' }}</div>
            <div><strong>Endereço:</strong> {{ $serviceOrder->customer?->address ?? '-' }} - {{ $serviceOrder->customer?->city ?? '' }}</div>
        </div>

        <div class="section-box">
            <div class="section-title">Dados do Veículo</div>
            <div><strong>Veículo:</strong> {{ $serviceOrder->vehicle?->brand }} {{ $serviceOrder->vehicle?->model }} ({{ $serviceOrder->vehicle?->year ?? 'Ano -' }})</div>
            <div><strong>Placa:</strong> <span style="font-family: monospace; font-weight: bold; color: #0284c7;">{{ $serviceOrder->vehicle?->plate }}</span></div>
            <div><strong>KM Entrada:</strong> {{ $serviceOrder->entry_km ? number_format($serviceOrder->entry_km, 0, ',', '.') . ' km' : '-' }}</div>
            <div><strong>Data Entrada:</strong> {{ $serviceOrder->entry_date?->format('d/m/Y') }} | <strong>Técnico:</strong> {{ $serviceOrder->technician?->name ?? 'Oficina' }}</div>
        </div>
    </div>

    <!-- Sintoma e Diagnóstico -->
    <div class="section-box">
        <div class="section-title">Reclamação do Cliente & Diagnóstico Técnico</div>
        <div style="margin-bottom: 6px;"><strong>Defeito Relatado:</strong> {{ $serviceOrder->reported_defect }}</div>
        @if($serviceOrder->technical_diagnosis)
            <div style="margin-bottom: 6px;"><strong>Laudo / Diagnóstico Elétrico:</strong> {{ $serviceOrder->technical_diagnosis }}</div>
        @endif
        @if($serviceOrder->solution_applied)
            <div><strong>Serviço Realizado / Testes:</strong> {{ $serviceOrder->solution_applied }}</div>
        @endif
    </div>

    <!-- Peças Aplicadas -->
    @if($serviceOrder->items->count() > 0)
        <div class="section-box">
            <div class="section-title">Peças & Componentes Elétricos Aplicados</div>
            <table>
                <thead>
                    <tr>
                        <th>Código/SKU</th>
                        <th>Descrição da Peça</th>
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

    <!-- Mão de Obra -->
    @if($serviceOrder->services->count() > 0)
        <div class="section-box">
            <div class="section-title">Mão de Obra & Serviços Executados</div>
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

    <!-- Registro Fotográfico de Resguardo da Oficina -->
    @if($serviceOrder->photos->count() > 0)
        <div class="section-box" style="page-break-inside: avoid;">
            <div class="section-title">Registro Fotográfico de Entrada / Diagnóstico / Conclusão</div>
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                @foreach($serviceOrder->photos as $photo)
                    <div style="width: 140px; border: 1px solid #e2e8f0; border-radius: 4px; padding: 4px; background: #f8fafc;">
                        <img src="{{ asset('storage/' . $photo->file_path) }}" style="width: 100%; height: 90px; object-fit: cover; border-radius: 2px;">
                        <div style="font-size: 9px; font-weight: bold; color: #0284c7; margin-top: 2px;">[{{ strtoupper($photo->stage) }}]</div>
                        <div style="font-size: 9px; color: #334155; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $photo->title ?? 'Foto' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Totais -->
    <div class="totals-box">
        <table class="totals-table">
            <tr>
                <td>Subtotal Peças:</td>
                <td class="text-right">R$ {{ number_format($serviceOrder->products_total, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Subtotal Mão de Obra:</td>
                <td class="text-right">R$ {{ number_format($serviceOrder->services_total, 2, ',', '.') }}</td>
            </tr>
            @if($serviceOrder->discount > 0)
                <tr>
                    <td style="color: #e11d48;">Desconto:</td>
                    <td class="text-right" style="color: #e11d48;">- R$ {{ number_format($serviceOrder->discount, 2, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="grand-total">
                <td>TOTAL GERAL:</td>
                <td class="text-right">R$ {{ number_format($serviceOrder->total_amount, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Termos e Garantia -->
    <div class="terms">
        <strong>Termo de Garantia:</strong> Garantia legal de 90 (noventa) dias para serviços e peças instaladas, conforme Art. 26 do Código de Defesa do Consumidor, contados a partir da data de entrega do veículo. A garantia não cobre avarias decorrentes de mau uso, sobretensão elétrica provocada por curto externo ou violação de lacres.
    </div>

    <!-- Assinaturas -->
    <div class="signatures">
        <div class="sign-line">
            AUTO COLD - Responsável Técnico
        </div>
        <div class="sign-line">
            {{ $serviceOrder->customer?->name }} (Cliente)
        </div>
    </div>

</body>
</html>
