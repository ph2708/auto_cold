<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query()->with('vehicles')->withCount('serviceOrders');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%")
                  ->orWhereHas('vehicles', function ($vq) use ($search) {
                      $vq->where('plate', 'like', "%{$search}%")
                         ->orWhere('model', 'like', "%{$search}%");
                  });
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    /**
     * Consulta em tempo real (AJAX) se o CPF já está cadastrado.
     */
    public function checkCpf(Request $request)
    {
        $rawCpf = $request->get('cpf', '');
        $cleanCpf = preg_replace('/\D/', '', $rawCpf);

        if (strlen($cleanCpf) < 11) {
            return response()->json(['exists' => false]);
        }

        // Buscar tanto com formatação quanto sem
        $customer = Customer::where(function ($q) use ($rawCpf, $cleanCpf) {
            $q->where('document_number', $rawCpf)
              ->orWhere('document_number', $cleanCpf)
              ->orWhereRaw("REPLACE(REPLACE(REPLACE(document_number, '.', ''), '-', ''), '/', '') = ?", [$cleanCpf]);
        })->first();

        if ($customer) {
            return response()->json([
                'exists' => true,
                'customer_id' => $customer->id,
                'name' => $customer->name,
                'whatsapp' => $customer->whatsapp ?? $customer->phone ?? 'Não informado',
                'edit_url' => route('customers.edit', $customer),
                'message' => "Este CPF já está cadastrado para: {$customer->name}."
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Cria ou retorna o Cliente Avulso / Balcão da Oficina
     */
    public function getOrCreateGeneric(Request $request)
    {
        $generic = Customer::firstOrCreate(
            ['name' => 'CLIENTE AVULSO / BALCÃO'],
            [
                'phone' => '(00) 0000-0000',
                'notes' => 'Cadastro rápido para atendimentos sem identificação de cliente.'
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'customer' => $generic
            ]);
        }

        return redirect()->route('service_orders.create', ['customer_id' => $generic->id])
            ->with('success', 'Cliente Avulso selecionado com sucesso!');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            // Veículo inicial (opcional)
            'plate' => 'nullable|string|max:10',
            'brand' => 'nullable|string|max:50',
            'model' => 'nullable|string|max:100',
            'year' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:30',
        ]);

        // Se informou CPF, verificar se já existe
        if (!empty($validated['document_number'])) {
            $cleanCpf = preg_replace('/\D/', '', $validated['document_number']);
            $existing = Customer::where(function ($q) use ($validated, $cleanCpf) {
                $q->where('document_number', $validated['document_number'])
                  ->orWhereRaw("REPLACE(REPLACE(REPLACE(document_number, '.', ''), '-', ''), '/', '') = ?", [$cleanCpf]);
            })->first();

            if ($existing) {
                return back()->withInput()->withErrors([
                    'document_number' => "Este CPF já está cadastrado para o cliente: {$existing->name} (ID: #{$existing->id})."
                ]);
            }
        }

        $customer = Customer::create($validated);

        if (!empty($validated['plate'])) {
            Vehicle::create([
                'customer_id' => $customer->id,
                'plate' => strtoupper($validated['plate']),
                'brand' => $validated['brand'] ?? 'Geral',
                'model' => $validated['model'] ?? 'Geral',
                'year' => $validated['year'] ?? null,
                'color' => $validated['color'] ?? null,
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Cliente e veículo cadastrados com sucesso!');
    }

    public function edit(Customer $customer)
    {
        $customer->load('vehicles');
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Dados do cliente atualizados!');
    }

    public function storeVehicle(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'plate' => 'required|string|max:10|unique:vehicles,plate',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:100',
            'year' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:30',
            'current_km' => 'nullable|integer',
        ]);

        $validated['customer_id'] = $customer->id;
        $validated['plate'] = strtoupper($validated['plate']);

        Vehicle::create($validated);

        return back()->with('success', 'Veículo adicionado ao cliente!');
    }
}
