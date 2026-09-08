<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Models\ServiceOrderService;
use App\Models\PurchaseOrder;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Configurações Iniciais da Auto Cold
        Setting::set('company_name', 'AUTO COLD');
        Setting::set('company_subtitle', 'Elétrica e Ar Condicionado');
        Setting::set('owner_name', 'JULIANO RIBEIRO');
        Setting::set('whatsapp', '64993295596');
        Setting::set('phone', '(64) 99329-5596');
        Setting::set('slogan', 'CONFIANÇA E QUALIDADE: A COMBINAÇÃO PERFEITA PARA O SEU VEÍCULO.');
        Setting::set('address', 'R. Monte Alegre, 271 - Jardim Liberdade');
        Setting::set('city', 'Itumbiara - GO, 75510-090');
        Setting::set('latitude', '-18.4079971');
        Setting::set('longitude', '-49.2249619');
        Setting::set('maps_link', 'https://www.google.com/maps/place/R.+Monte+Alegre,+271+-+Jardim+Liberdade,+Itumbiara+-+GO,+75510-090/@-18.4079971,-49.2256056,238m/data=!3m2!1e3!4b1!4m6!3m5!1s0x94a10d0d1df7616d:0x234bb98b2302d442!8m2!3d-18.4079971!4d-49.2249619!16s%2Fg%2F11wvgpf2r5?entry=ttu');
        Setting::set('primary_color', '#06b6d4');
        Setting::set('secondary_color', '#2563eb');
        Setting::set('logo_dark', 'images/logo-dark.png');
        Setting::set('logo_light', 'images/logo-light.png');
        Setting::set('logo_height', '48');

        // 1. Roles
        $adminRole = Role::create([
            'name' => 'admin',
            'label' => 'Administrador',
            'description' => 'Acesso total a todas as funções, relatórios, cadastros e configurações do sistema.'
        ]);

        $managerRole = Role::create([
            'name' => 'manager',
            'label' => 'Gerente',
            'description' => 'Gerencia compras, fornecedores, estoque e usuários operacionais.'
        ]);

        $electricianRole = Role::create([
            'name' => 'electrician',
            'label' => 'Eletricista / Técnico',
            'description' => 'Consulta peças elétricas e requisita materiais para veículos/serviços.'
        ]);

        $stockistRole = Role::create([
            'name' => 'stockist',
            'label' => 'Estoquista / Atendente',
            'description' => 'Registra entradas e saídas de peças e gerencia localização no almoxarifado.'
        ]);

        // 2. Usuários Iniciais
        $adminUser = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Administrador Auto Cold',
            'username' => 'admin',
            'email' => 'admin@autocold.com.br',
            'phone' => '(64) 99329-5596',
            'password' => Hash::make('admin123'),
            'is_active' => true,
        ]);

        $eletricistaUser = User::create([
            'role_id' => $electricianRole->id,
            'name' => 'Carlos Eletricista',
            'username' => 'carlos.eletrica',
            'email' => 'carlos@autocold.com.br',
            'phone' => '(64) 99999-0002',
            'password' => Hash::make('eletrica123'),
            'is_active' => true,
        ]);

        $estoquistaUser = User::create([
            'role_id' => $stockistRole->id,
            'name' => 'Marcos Estoque',
            'username' => 'marcos.estoque',
            'email' => 'marcos@autocold.com.br',
            'phone' => '(64) 99999-0003',
            'password' => Hash::make('estoque123'),
            'is_active' => true,
        ]);

        // 3. Fornecedores Especializados em Auto Elétrica & Peças
        $fornecedorBosch = Supplier::create([
            'corporate_name' => 'Robert Bosch Ltda',
            'trade_name' => 'Bosch Autopeças',
            'document_number' => '45.990.181/0001-89',
            'state_registration' => '112.456.789.110',
            'email' => 'vendas.auto@bosch.com.br',
            'phone' => '(11) 3741-2000',
            'whatsapp' => '(11) 98765-4321',
            'contact_person' => 'Ricardo Silva',
            'city' => 'Campinas',
            'state' => 'SP',
            'address' => 'Via Anhangüera, km 98',
            'notes' => 'Fornecedor principal de alternadores, motores de partida, sensores e velas.',
            'is_active' => true,
        ]);

        $fornecedorMoura = Supplier::create([
            'corporate_name' => 'Acumuladores Moura S/A',
            'trade_name' => 'Baterias Moura',
            'document_number' => '10.584.090/0001-92',
            'state_registration' => '020.123.456.789',
            'email' => 'comercial@moura.com.br',
            'phone' => '(81) 3411-1000',
            'whatsapp' => '(11) 97777-8888',
            'contact_person' => 'Fernanda Lima',
            'city' => 'Belo Jardim',
            'state' => 'PE',
            'address' => 'Rua Cel. Antonio Marinho, 65',
            'notes' => 'Distribuidor direto de baterias 12V e 24V para linha leve e pesada.',
            'is_active' => true,
        ]);

        $fornecedorDNI = Supplier::create([
            'corporate_name' => 'DNI Indústria Eletrônica e Autopeças Ltda',
            'trade_name' => 'DNI Eletrônica Automotiva',
            'document_number' => '54.218.441/0001-50',
            'state_registration' => '108.987.654.321',
            'email' => 'vendas@dni.com.br',
            'phone' => '(11) 3933-8888',
            'whatsapp' => '(11) 98888-4433',
            'contact_person' => 'Eduardo Martins',
            'city' => 'São Paulo',
            'state' => 'SP',
            'notes' => 'Especialista em relés auxiliares, sirenes, chicotes e conectores.',
            'is_active' => true,
        ]);

        $fornecedorOsram = Supplier::create([
            'corporate_name' => 'Osram do Brasil Lâmpadas Elétricas Ltda',
            'trade_name' => 'Osram Iluminação Automotiva',
            'document_number' => '61.123.456/0001-01',
            'email' => 'contato.auto@osram.com.br',
            'phone' => '(11) 3687-1000',
            'whatsapp' => '(11) 99123-5566',
            'contact_person' => 'Juliana Costa',
            'city' => 'Osasco',
            'state' => 'SP',
            'notes' => 'Linha completa de lâmpadas halógenas, LED e xenon automotivos.',
            'is_active' => true,
        ]);

        // 4. Categorias com foco em Auto Elétrica & Ar Condicionado
        $catBaterias = Category::create([
            'name' => 'Baterias & Acumuladores',
            'slug' => 'baterias-acumuladores',
            'department' => 'eletrica',
            'description' => 'Baterias automotivas 12V/24V para veículos leves, utilitários e caminhões.'
        ]);

        $catPartidaAlternador = Category::create([
            'name' => 'Motores de Partida & Alternadores',
            'slug' => 'partida-e-alternadores',
            'department' => 'eletrica',
            'description' => 'Peças completas e reparos: induzidos, estatores, placas de diodo, reguladores e escovas.'
        ]);

        $catIluminacao = Category::create([
            'name' => 'Iluminação & Sinalização',
            'slug' => 'iluminacao-sinalizacao',
            'department' => 'eletrica',
            'description' => 'Lâmpadas H1, H4, H7, lâmpadas de painel, lanternas e relés de pisca.'
        ]);

        $catRelesFusiveis = Category::create([
            'name' => 'Relés, Fusíveis & Chicotes',
            'slug' => 'reles-fusiveis-chicotes',
            'department' => 'eletrica',
            'description' => 'Relés de 4 e 5 pinos, caixas de fusíveis tipo faca, mini e max, terminais elétricos.'
        ]);

        $catSensoresInjecao = Category::create([
            'name' => 'Sensores & Injeção Eletrônica',
            'slug' => 'sensores-injecao-eletronica',
            'department' => 'eletrica',
            'description' => 'Sonda lambda, sensor de rotação, sensor de temperatura, atuadores e conectores.'
        ]);

        $catArCondicionado = Category::create([
            'name' => 'Ar Condicionado Automotivo',
            'slug' => 'ar-condicionado-automotivo',
            'department' => 'eletrica',
            'description' => 'Gás refrigerante R134a, óleo PAG, compressores, condensadores e filtros de cabine.'
        ]);

        // 5. Produtos / Peças Elétricas
        $p1 = Product::create([
            'category_id' => $catBaterias->id,
            'default_supplier_id' => $fornecedorMoura->id,
            'name' => 'Bateria Automotiva 60Ah 12V Polo Direito (M60GD)',
            'sku' => 'BAT-MOU-60',
            'barcode' => '7891234560012',
            'voltage' => '12V',
            'amperage' => '60Ah',
            'oem_code' => 'M60GD',
            'vehicle_compatibility' => 'Linha leve universal: Gol, Onix, HB20, Fox, Polo, Ka, Fiesta',
            'unit' => 'UN',
            'location' => 'Prateleira A1 - Piso',
            'cost_price' => 380.00,
            'selling_price' => 540.00,
            'min_stock' => 5,
            'current_stock' => 8,
            'is_active' => true,
        ]);

        $p2 = Product::create([
            'category_id' => $catBaterias->id,
            'default_supplier_id' => $fornecedorMoura->id,
            'name' => 'Bateria Caminhão / Utilitário 150Ah 12V (M150BD)',
            'sku' => 'BAT-MOU-150',
            'barcode' => '7891234560029',
            'voltage' => '12V',
            'amperage' => '150Ah',
            'oem_code' => 'M150BD',
            'vehicle_compatibility' => 'Caminhões Mercedes-Benz, Ford Cargo, Volvo VM, Tratores',
            'unit' => 'UN',
            'location' => 'Prateleira A2 - Piso',
            'cost_price' => 820.00,
            'selling_price' => 1190.00,
            'min_stock' => 2,
            'current_stock' => 3,
            'is_active' => true,
        ]);

        $p3 = Product::create([
            'category_id' => $catPartidaAlternador->id,
            'default_supplier_id' => $fornecedorBosch->id,
            'name' => 'Motor de Arranque / Partida 12V Bosch',
            'sku' => 'ARR-BOS-12V-01',
            'barcode' => '7891234560036',
            'voltage' => '12V',
            'amperage' => '1.1kW',
            'oem_code' => 'F000AL0401',
            'vehicle_compatibility' => 'VW Gol, Voyage, Saveiro, Fox motor EA111 1.0 e 1.6',
            'unit' => 'UN',
            'location' => 'Prateleira B1 - Caixa 04',
            'cost_price' => 420.00,
            'selling_price' => 650.00,
            'min_stock' => 2,
            'current_stock' => 1,
            'is_active' => true,
        ]);

        $p4 = Product::create([
            'category_id' => $catPartidaAlternador->id,
            'default_supplier_id' => $fornecedorBosch->id,
            'name' => 'Regulador de Voltagem 14V Bosch 90A/120A',
            'sku' => 'REG-BOS-14V-90A',
            'barcode' => '7891234560043',
            'voltage' => '12V',
            'amperage' => '90A',
            'oem_code' => 'F00M145225',
            'vehicle_compatibility' => 'Alternadores Bosch Fiat Palio, Strada, Uno, Punto Fire',
            'unit' => 'UN',
            'location' => 'Gaveteiro G3 - Gaveta 12',
            'cost_price' => 85.00,
            'selling_price' => 145.00,
            'min_stock' => 4,
            'current_stock' => 2,
            'is_active' => true,
        ]);

        $p5 = Product::create([
            'category_id' => $catRelesFusiveis->id,
            'default_supplier_id' => $fornecedorDNI->id,
            'name' => 'Relé Auxiliar Universal 12V 40A 4 Pinos DNI',
            'sku' => 'REL-DNI-0101',
            'barcode' => '7891234560050',
            'voltage' => '12V',
            'amperage' => '40A',
            'oem_code' => 'DNI-0101',
            'vehicle_compatibility' => 'Farol de milha, buzina, ar condicionado, bomba de combustível',
            'unit' => 'UN',
            'location' => 'Gaveteiro G1 - Gaveta 01',
            'cost_price' => 12.00,
            'selling_price' => 28.00,
            'min_stock' => 15,
            'current_stock' => 28,
            'is_active' => true,
        ]);

        $p6 = Product::create([
            'category_id' => $catArCondicionado->id,
            'default_supplier_id' => $fornecedorBosch->id,
            'name' => 'Gás Refrigerante R134a Automotivo Lata 13.6kg',
            'sku' => 'GAS-R134A-13KG',
            'barcode' => '7891234560067',
            'voltage' => 'Universal',
            'vehicle_compatibility' => 'Carga e recarga de ar condicionado automotivo em geral',
            'unit' => 'KG',
            'location' => 'Almoxarifado Central - Cilindros',
            'cost_price' => 540.00,
            'selling_price' => 890.00,
            'min_stock' => 2,
            'current_stock' => 4,
            'is_active' => true,
        ]);

        // Movimentações de Entrada de Estoque Iniciais (Compras com Fornecedores)
        StockMovement::create([
            'product_id' => $p1->id,
            'supplier_id' => $fornecedorMoura->id,
            'user_id' => $adminUser->id,
            'type' => 'in',
            'reason' => 'purchase',
            'document_number' => 'NF-98421',
            'quantity' => 10,
            'unit_cost' => $p1->cost_price,
            'unit_price' => $p1->selling_price,
            'total_amount' => 10 * $p1->cost_price,
            'notes' => 'Entrada lote mensal baterias 60Ah Moura.',
            'created_at' => now()->subDays(5),
        ]);

        StockMovement::create([
            'product_id' => $p3->id,
            'supplier_id' => $fornecedorBosch->id,
            'user_id' => $adminUser->id,
            'type' => 'in',
            'reason' => 'purchase',
            'document_number' => 'NF-77341',
            'quantity' => 2,
            'unit_cost' => $p3->cost_price,
            'unit_price' => $p3->selling_price,
            'total_amount' => 2 * $p3->cost_price,
            'notes' => 'Motores de arranque para linha EA111.',
            'created_at' => now()->subDays(3),
        ]);

        StockMovement::create([
            'product_id' => $p6->id,
            'supplier_id' => $fornecedorBosch->id,
            'user_id' => $adminUser->id,
            'type' => 'in',
            'reason' => 'purchase',
            'document_number' => 'NF-55120',
            'quantity' => 4,
            'unit_cost' => $p6->cost_price,
            'unit_price' => $p6->selling_price,
            'total_amount' => 4 * $p6->cost_price,
            'notes' => 'Cilindros de gás ecológico R134a para recargas de ar condicionado.',
            'created_at' => now()->subDays(2),
        ]);

        // 6. Clientes e Veículos
        $c1 = Customer::create([
            'name' => 'Roberto Fernandes da Silva',
            'document_number' => '284.912.848-12',
            'phone' => '(64) 99245-1234',
            'whatsapp' => '64992451234',
            'email' => 'roberto.silva@gmail.com',
            'city' => 'Itumbiara',
            'state' => 'GO',
            'address' => 'Rua Santos Dumont, 450 - Bairro Afonso Pena',
        ]);

        $v1 = Vehicle::create([
            'customer_id' => $c1->id,
            'plate' => 'BRA2E19',
            'brand' => 'Volkswagen',
            'model' => 'Gol G6 1.6 MSI Flex',
            'year' => '2015/2016',
            'color' => 'Prata',
            'fuel_type' => 'Flex',
            'current_km' => 64500,
        ]);

        $c2 = Customer::create([
            'name' => 'Mariana Alcantara Santos',
            'document_number' => '391.842.108-55',
            'phone' => '(64) 99876-5432',
            'whatsapp' => '64998765432',
            'email' => 'mariana.alcantara@hotmail.com',
            'city' => 'Itumbiara',
            'state' => 'GO',
            'address' => 'Av. Modesto de Carvalho, 1120',
        ]);

        $v2 = Vehicle::create([
            'customer_id' => $c2->id,
            'plate' => 'RTO9A88',
            'brand' => 'Fiat',
            'model' => 'Strada Freedom 1.3 Cabine Dupla',
            'year' => '2022/2023',
            'color' => 'Preto',
            'fuel_type' => 'Flex',
            'current_km' => 38000,
        ]);

        // 7. Ordens de Serviço (OS) de Exemplo
        $os1 = ServiceOrder::create([
            'order_number' => 'OS-2026-0001',
            'customer_id' => $c1->id,
            'vehicle_id' => $v1->id,
            'user_id' => $eletricistaUser->id,
            'status' => 'in_progress',
            'reported_defect' => 'Veículo não dá partida pela manhã, faz barulho de estalo no motor de arranque mas não gira o motor.',
            'technical_diagnosis' => 'Bateria com carga normal (12.6V). Testado motor de arranque na bancada: induzido em curto e escovas desgastadas.',
            'solution_applied' => 'Substituição do motor de arranque e revisão do cabo positivo da bateria.',
            'entry_km' => 64500,
            'entry_date' => now()->subDays(2),
            'expected_date' => now()->addDays(1),
        ]);

        ServiceOrderItem::create([
            'service_order_id' => $os1->id,
            'product_id' => $p3->id,
            'quantity' => 1,
            'unit_cost' => $p3->cost_price,
            'unit_price' => $p3->selling_price,
            'total_amount' => $p3->selling_price,
        ]);

        ServiceOrderService::create([
            'service_order_id' => $os1->id,
            'description' => 'Mão de obra para troca de motor de arranque e teste de carga no alternador',
            'quantity' => 1,
            'unit_price' => 220.00,
            'total_amount' => 220.00,
        ]);

        $os1->recalculateTotals();

        $os2 = ServiceOrder::create([
            'order_number' => 'OS-2026-0002',
            'customer_id' => $c2->id,
            'vehicle_id' => $v2->id,
            'user_id' => $eletricistaUser->id,
            'status' => 'completed',
            'reported_defect' => 'Ar condicionado parou de gelar no trânsito e luz da bateria piscou no painel.',
            'technical_diagnosis' => 'Vazamento de gás na válvula de serviço e alternador com regulador de voltagem com falha.',
            'solution_applied' => 'Carga de gás R134a ecológica com contraste, higienização com ozônio e troca da bateria Moura 60Ah.',
            'entry_km' => 38000,
            'entry_date' => now()->subDays(3),
            'completion_date' => now()->subDay(),
        ]);

        ServiceOrderItem::create([
            'service_order_id' => $os2->id,
            'product_id' => $p1->id,
            'quantity' => 1,
            'unit_cost' => $p1->cost_price,
            'unit_price' => $p1->selling_price,
            'total_amount' => $p1->selling_price,
        ]);

        ServiceOrderItem::create([
            'service_order_id' => $os2->id,
            'product_id' => $p5->id,
            'quantity' => 1,
            'unit_cost' => $p5->cost_price,
            'unit_price' => $p5->selling_price,
            'total_amount' => $p5->selling_price,
        ]);

        ServiceOrderService::create([
            'service_order_id' => $os2->id,
            'description' => 'Serviço de recarga de gás R134a + Teste de vazamento UV + Higienização de Ar Condicionado',
            'quantity' => 1,
            'unit_price' => 280.00,
            'total_amount' => 280.00,
        ]);

        ServiceOrderService::create([
            'service_order_id' => $os2->id,
            'description' => 'Diagnóstico elétrico com scanner e teste de fuga de corrente',
            'quantity' => 1,
            'unit_price' => 120.00,
            'total_amount' => 120.00,
        ]);

        $os2->recalculateTotals();

        // 8. Gastos com Pedidos / Compras de Peças Registradas (Mercado Livre / Shopee / Web / Distribuidor)
        PurchaseOrder::create([
            'order_code' => 'PED-2026-0001',
            'origin' => 'Mercado Livre',
            'item_name' => 'Módulo de Injeção Bosch ME7.5.30 Gol G5',
            'external_order_number' => 'MLB-200049281',
            'tracking_code' => 'NL123456789BR',
            'tracking_url' => 'https://rastreamento.correios.com.br',
            'service_order_id' => $os1->id,
            'user_id' => $adminUser->id,
            'quantity' => 1,
            'unit_cost' => 650.00,
            'shipping_cost' => 35.00,
            'total_cost' => 685.00,
            'status' => 'shipped',
            'purchase_date' => now()->subDays(1),
            'expected_delivery_date' => now()->addDays(2),
            'notes' => 'Vendedor Mercado Líder Platinum com garantia de 6 meses.',
        ]);

        PurchaseOrder::create([
            'order_code' => 'PED-2026-0002',
            'origin' => 'Shopee',
            'item_name' => 'Kit 100 Conectores Chicote Automotivo 2 e 4 Vias',
            'external_order_number' => 'SHP-99214012',
            'tracking_code' => 'BR987654321SP',
            'user_id' => $adminUser->id,
            'quantity' => 1,
            'unit_cost' => 89.90,
            'shipping_cost' => 0.00,
            'total_cost' => 89.90,
            'status' => 'pending',
            'purchase_date' => now(),
            'expected_delivery_date' => now()->addDays(5),
            'notes' => 'Reposição de conectores para bancada elétrica.',
        ]);

        PurchaseOrder::create([
            'order_code' => 'PED-2026-0003',
            'origin' => 'Distribuidora Local',
            'item_name' => 'Compressor Denso 12V Ar Condicionado Fiat Strada',
            'external_order_number' => 'DST-44109',
            'tracking_code' => 'ENTREGA-MOTOBOY',
            'service_order_id' => $os2->id,
            'user_id' => $adminUser->id,
            'quantity' => 1,
            'unit_cost' => 1250.00,
            'shipping_cost' => 20.00,
            'total_cost' => 1270.00,
            'status' => 'delivered',
            'purchase_date' => now()->subDays(3),
            'received_at' => now()->subDays(2),
            'notes' => 'Compressor novo original com nota fiscal e garantia.',
        ]);
    }
}
