<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\PurchaseOrderController;

// Landing Page Pública da Auto Cold
Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/site', function () {
    return view('landing');
})->name('landing');


// Rota de Diagnóstico do Banco Neon / Vercel
Route::get('/health-db', function () {
    try {
        $db = \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $tables = \Illuminate\Support\Facades\DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
        $tablesList = array_map(fn($t) => $t->table_name ?? $t->TABLE_NAME, $tables);

        return response()->json([
            'status' => 'success',
            'message' => 'Conexão com Banco de Dados OK!',
            'driver' => $driver,
            'database' => $dbName,
            'tables_count' => count($tablesList),
            'tables' => $tablesList,
            'neon_env_detected' => !empty(env('DATABASE_URL') ?: env('POSTGRES_URL') ?: env('STORAGE_URL')),
            'app_key_set' => !empty(config('app.key')),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'app_key_set' => !empty(config('app.key')),
            'env_database_url' => substr(env('DATABASE_URL') ?: env('POSTGRES_URL') ?: env('STORAGE_URL') ?: 'NÃO DEFINIDO', 0, 15) . '...',
        ], 500);
    }
});

// Rota rápida de auto-migração caso não tenha CLI local
Route::get('/run-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate --force');
        \Illuminate\Support\Facades\Artisan::call('db:seed --force');
        return response()->json([
            'status' => 'success',
            'message' => 'Migrations e Seeders executados com sucesso!',
            'output' => \Illuminate\Support\Facades\Artisan::output()
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas Protegidas por Autenticação e Role
Route::middleware(['auth'])->group(function () {
    
    // Dashboard (Todos os autenticados)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Catálogo de Produtos / Peças Elétricas
    Route::resource('products', ProductController::class);

    // Controle de Fornecedores
    Route::resource('suppliers', SupplierController::class);

    // Clientes & Veículos
    Route::get('customers/check-cpf', [CustomerController::class, 'checkCpf'])->name('customers.check_cpf');
    Route::post('customers/quick-generic', [CustomerController::class, 'getOrCreateGeneric'])->name('customers.generic');
    Route::get('service_orders/lookup-vehicle', [ServiceOrderController::class, 'lookupVehicle'])->name('service_orders.lookup_vehicle');
    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/vehicles', [CustomerController::class, 'storeVehicle'])->name('customers.vehicles.store');

    // Ordens de Serviço (OS) & Orçamentos
    Route::resource('service_orders', ServiceOrderController::class);
    Route::patch('service_orders/{service_order}/status', [ServiceOrderController::class, 'updateStatus'])->name('service_orders.status.update');
    Route::post('service_orders/{service_order}/approve-budget', [ServiceOrderController::class, 'approveBudget'])->name('service_orders.budget.approve');
    Route::post('service_orders/{service_order}/items', [ServiceOrderController::class, 'addItem'])->name('service_orders.items.store');
    Route::delete('service_orders/{service_order}/items/{item}', [ServiceOrderController::class, 'removeItem'])->name('service_orders.items.destroy');
    Route::post('service_orders/{service_order}/services', [ServiceOrderController::class, 'addService'])->name('service_orders.services.store');
    Route::delete('service_orders/{service_order}/services/{service}', [ServiceOrderController::class, 'removeService'])->name('service_orders.services.destroy');
    Route::post('service_orders/{service_order}/photos', [ServiceOrderController::class, 'uploadPhotos'])->name('service_orders.photos.store');
    Route::delete('service_orders/{service_order}/photos/{photo}', [ServiceOrderController::class, 'deletePhoto'])->name('service_orders.photos.destroy');
    Route::get('service_orders/{service_order}/print', [ServiceOrderController::class, 'print'])->name('service_orders.print');
    Route::get('service_orders/{service_order}/print-budget', [ServiceOrderController::class, 'printBudget'])->name('service_orders.print_budget');

    // Pedidos de Peças a Chegar (Mercado Livre, Shopee, Loja Online, etc.)
    Route::resource('purchase_orders', PurchaseOrderController::class);
    Route::post('purchase_orders/{purchase_order}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase_orders.receive');

    // Controle de Estoque (Entradas, Saídas e Kardex)
    Route::get('/stock', [StockMovementController::class, 'index'])->name('stock.index');
    Route::get('/stock/entry', [StockMovementController::class, 'createEntry'])->name('stock.entry');
    Route::post('/stock/entry', [StockMovementController::class, 'storeEntry'])->name('stock.entry.store');
    Route::get('/stock/exit', [StockMovementController::class, 'createExit'])->name('stock.exit');
    Route::post('/stock/exit', [StockMovementController::class, 'storeExit'])->name('stock.exit.store');

    // Gestão de Usuários e Controle de Acessos (Apenas Admin e Gerente)
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::resource('users', UserController::class)->except(['show', 'destroy']);
        
        // Personalização Visual e Configurações da Empresa
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    });
});
