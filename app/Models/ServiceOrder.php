<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'vehicle_id',
        'user_id',
        'status',
        'reported_defect',
        'technical_diagnosis',
        'solution_applied',
        'services_total',
        'products_total',
        'discount',
        'total_amount',
        'entry_km',
        'entry_date',
        'expected_date',
        'completion_date',
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'services_total' => 'decimal:2',
            'products_total' => 'decimal:2',
            'discount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'entry_date' => 'date',
            'expected_date' => 'date',
            'completion_date' => 'date',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(ServiceOrderItem::class);
    }

    public function services()
    {
        return $this->hasMany(ServiceOrderService::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function photos()
    {
        return $this->hasMany(ServiceOrderPhoto::class);
    }

    public function beforePhotos()
    {
        return $this->photos()->where('stage', 'before');
    }

    public function diagnosticPhotos()
    {
        return $this->photos()->where('stage', 'diagnostic');
    }

    public function afterPhotos()
    {
        return $this->photos()->where('stage', 'after');
    }

    public function getProductsCostAttribute(): float
    {
        return (float)$this->items->sum(function ($item) {
            return $item->quantity * $item->unit_cost;
        });
    }

    public function getProductsProfitAttribute(): float
    {
        return (float)($this->products_total - $this->products_cost);
    }

    public function getNetProfitAttribute(): float
    {
        // Lucro Líquido = Mão de Obra (100%) + Margem das Peças - Descontos
        return max(0, ($this->services_total + $this->products_profit) - (float)$this->discount);
    }

    public function getProfitMarginPercentAttribute(): float
    {
        if ($this->total_amount <= 0) return 0;
        return round(($this->net_profit / $this->total_amount) * 100, 1);
    }

    public function recalculateTotals(): void
    {
        $this->services_total = $this->services()->sum('total_amount');
        $this->products_total = $this->items()->sum('total_amount');
        $this->total_amount = max(0, ($this->services_total + $this->products_total) - (float)$this->discount);
        $this->save();
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'budget' => 'Orçamento',
            'approved' => 'Aprovada',
            'in_progress' => 'Em Execução',
            'waiting_parts' => 'Aguardando Peças',
            'completed' => 'Concluída / Testada',
            'delivered' => 'Entregue / Faturada',
            'cancelled' => 'Cancelada',
            default => $this->status,
        };
    }
}
