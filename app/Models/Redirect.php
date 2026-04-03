<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_url',
        'to_url',
        'status_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'status_code' => 'int',
    ];

    /**
     * Всегда храним from_url в нормализованном виде,
     * чтобы админ мог вставлять как полный, так и относительный URL.
     */
    public function setFromUrlAttribute(string $value): void
    {
        $this->attributes['from_url'] = self::normalizePath($value);
    }

    /**
     * Нормализация пути, чтобы from_url и запрос сопоставлялись одинаково.
     */
    public static function normalizePath(string $path): string
    {
        // Очищаем управление/невидимые символы и пробелы по краям
        $path = preg_replace('/[[:^print:]]+/u', '', $path);
        $path = trim($path);

        if ($path === '') {
            return '/';
        }

        // Если внутри строки есть полный URL (даже с лишними символами перед ним) —
        // забираем только путь и query
        $httpPos = strpos($path, 'http://');
        $httpsPos = strpos($path, 'https://');

        if ($httpPos !== false || $httpsPos !== false) {
            $start = $httpPos !== false ? $httpPos : $httpsPos;
            $urlPart = substr($path, $start);

            $parsed = parse_url($urlPart);
            $normalized = ($parsed['path'] ?? '/').(isset($parsed['query']) ? '?'.$parsed['query'] : '');
        } else {
            $normalized = $path;
        }

        // Гарантируем ведущий слэш
        if ($normalized[0] !== '/') {
            $normalized = '/'.ltrim($normalized, '/');
        }

        // Удаляем лишний завершающий слэш (кроме корня)
        if ($normalized !== '/' && str_ends_with($normalized, '/')) {
            $normalized = rtrim($normalized, '/');
        }

        return $normalized;
    }
}
