<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'sku',
        'image_url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    // RELACIONES
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
