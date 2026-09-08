<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'corporate_name',
        'trade_name',
        'document_number',
        'state_registration',
        'email',
        'phone',
        'whatsapp',
        'contact_person',
        'zipcode',
        'address',
        'number',
        'neighborhood',
        'city',
        'state',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'default_supplier_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
