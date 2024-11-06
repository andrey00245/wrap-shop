<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    public const LANGUAGE_ID_UK = 1;
    public const LANGUAGE_ID_EN = 2;
    public const LANGUAGE_ID_RU = 3;

    public const LANGUAGE_UK = 'uk';
    public const LANGUAGE_EN = 'en';
    public const LANGUAGE_RU = 'ru';

    public const LANGUAGES = [
        ['name' => self::LANGUAGE_UK, 'id' => self::LANGUAGE_ID_UK],
        ['name' => self::LANGUAGE_EN, 'id' => self::LANGUAGE_ID_EN],
        ['name' => self::LANGUAGE_RU, 'id' => self::LANGUAGE_ID_RU],
    ];
}
