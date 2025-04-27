<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ReportAvailability extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'phone', 'email', 'product_id'];

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
