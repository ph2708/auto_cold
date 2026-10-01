<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrderLog extends Model
{
    protected $fillable = [
        'service_order_id',
        'user_id',
        'action',
        'from_value',
        'to_value',
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

    /**
     * Label amigável para a ação
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created'         => 'OS Criada',
            'status_changed'  => 'Status Alterado',
            'item_added'      => 'Peça Adicionada',
            'item_removed'    => 'Peça Removida',
            'service_added'   => 'Serviço Adicionado',
            'service_removed' => 'Serviço Removido',
            'budget_approved' => 'Orçamento Aprovado',
            'photo_added'     => 'Foto Anexada',
            'photo_removed'   => 'Foto Removida',
            'note_updated'    => 'Anotação Atualizada',
            default           => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    /**
     * Ícone Font Awesome para cada ação
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'created'         => 'fa-plus-circle text-cyan-500',
            'status_changed'  => 'fa-arrows-rotate text-amber-500',
            'item_added'      => 'fa-microchip text-blue-500',
            'item_removed'    => 'fa-trash text-rose-400',
            'service_added'   => 'fa-wrench text-emerald-500',
            'service_removed' => 'fa-minus text-rose-400',
            'budget_approved' => 'fa-check-circle text-emerald-600',
            'photo_added'     => 'fa-camera text-purple-500',
            'photo_removed'   => 'fa-image text-rose-400',
            'note_updated'    => 'fa-pen-to-square text-slate-400',
            default           => 'fa-circle-dot text-slate-400',
        };
    }
}
