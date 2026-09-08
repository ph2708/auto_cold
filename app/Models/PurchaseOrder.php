<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'supplier_id',
        'service_order_id',
        'product_id',
        'user_id',
        'origin',
        'external_order_number',
        'tracking_code',
        'tracking_url',
        'item_name',
        'quantity',
        'unit_cost',
        'shipping_cost',
        'total_cost',
        'status',
        'purchase_date',
        'expected_delivery_date',
        'received_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'purchase_date' => 'date',
            'expected_delivery_date' => 'date',
            'received_at' => 'datetime',
        ];
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Aguardando Envio',
            'shipped' => 'Em Trânsito / A Caminho',
            'delivered' => 'Entregue / Na Oficina',
            'cancelled' => 'Cancelado',
            default => $this->status,
        };
    }
}
