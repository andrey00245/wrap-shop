<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'text',
        'photo',
        'rating',
        'is_active',
        'moderation_status',
        'moderated_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'moderated_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function photoUrl(): ?string
    {
        if (blank($this->photo)) {
            return null;
        }

        return Storage::disk('public')->url($this->photo);
    }
}
