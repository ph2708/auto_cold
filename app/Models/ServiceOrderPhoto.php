<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrderPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'user_id',
        'stage', // before, after, diagnostic
        'file_path',
        'title',
        'description',
    ];

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStageLabelAttribute(): string
    {
        return match ($this->stage) {
            'before' => 'Antes / Entrada do Veículo',
            'diagnostic' => 'Diagnóstico / Peça Danificada',
            'after' => 'Depois / Concluído e Testado',
            default => $this->stage,
        };
    }
}
