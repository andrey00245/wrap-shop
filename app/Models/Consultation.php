<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $table = 'consultations';

    // Указываем поля, которые можно массово заполнять
    protected $fillable = [
        'name',
        'phone',
        'email',
        'comment',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
