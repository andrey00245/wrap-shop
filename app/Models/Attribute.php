<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasFactory,
        HasTranslations;

    public $translatable = ['name', 'values'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_id',
        'field_name',
        'name',
    ];

    protected $casts = [
        'name' => 'json',
    ];
}
