<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'supplier_id',
        'type', // in, out
        'reason',
        'quantity',
        'unit_cost',
        'unit_price',
        'total_amount',
        'document_number',
        'vehicle_plate',
        'service_order_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            'purchase' => 'Compra / Fornecedor',
            'adjustment_in' => 'Ajuste (+) Inventário',
            'return_in' => 'Devolução (OS / Cliente)',
            'service_order' => 'Aplicação em Veículo / OS',
            'direct_sale' => 'Venda Balcão',
            'internal_use' => 'Uso Interno Oficina',
            'loss_damage' => 'Perda / Avaria / Queima',
            'adjustment_out' => 'Ajuste (-) Inventário',
            default => $this->reason,
        };
    }
}
