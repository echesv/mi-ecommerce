<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'order_id',
        'stripe_payment_id',
        'amount',
        'status',
        'stripe_response',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'stripe_response' => 'json',
            'processed_at' => 'datetime',
        ];
    }

    // RELACIONES
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
