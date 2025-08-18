<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'total',
        'shipping_method',
        'novaposhta_warehouse_ref',
        'moysklad_id',
        'payment_status',
        'checkbox_receipt_id',
        'checkbox_status',
        'checkbox_response'
    ];

    protected $casts = [
        'total'             => 'decimal:2',
        'checkbox_response' => 'array',
        'checkbox_status'   => 'string', // всегда строка
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
