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
     * Нормализация пути для from_url и сопоставления с запросом.
     * Query string не включаем: редиректы старых URL почти всегда по пути; UTM в запросе не должен ломать match.
     */
    public static function normalizePath(string $path): string
    {
        // Очищаем управление/невидимые символы и пробелы по краям
        $path = preg_replace('/[[:^print:]]+/u', '', $path);
        $path = trim($path);

        if ($path === '') {
            return '/';
        }

        $httpPos = strpos($path, 'http://');
        $httpsPos = strpos($path, 'https://');

        if ($httpPos !== false || $httpsPos !== false) {
            $start = $httpPos !== false ? $httpPos : $httpsPos;
            $urlPart = substr($path, $start);
            $parsed = parse_url($urlPart);
            $normalized = $parsed['path'] ?? '/';
        } else {
            // Относительный путь или /a/b?x=1 — берём только path через parse_url
            $toParse = str_starts_with($path, '/')
                ? 'https://__redirect.invalid'.$path
                : 'https://__redirect.invalid/'.$path;
            $parsed = parse_url($toParse);
            $normalized = $parsed['path'] ?? '/';
        }

        if ($normalized === '') {
            $normalized = '/';
        }

        // Гарантируем ведущий слэш
        if (! str_starts_with($normalized, '/')) {
            $normalized = '/'.ltrim($normalized, '/');
        }

        // Удаляем лишний завершающий слэш (кроме корня)
        if ($normalized !== '/' && str_ends_with($normalized, '/')) {
            $normalized = rtrim($normalized, '/');
        }

        return mb_strtolower($normalized, 'UTF-8');
    }

    /**
     * Додаткові варіанти from_url для пошуку: старий сайт міг не мати /catalog/ у шляху,
     * а в індексі або посиланнях з’являється /{locale}/catalog/...
     *
     * @return list<string>
     */
    public static function legacyPathAliasesToTry(string $normalizedPath): array
    {
        $aliases = [];

        // /ru/catalog/foo/bar → /ru/foo/bar
        if (preg_match('#^/([a-z]{2})/catalog/(.+)$#i', $normalizedPath, $m)) {
            $aliases[] = '/'.$m[1].'/'.$m[2];
        }

        // /catalog/foo — коли дефолтна локаль без префікса в URL
        if (preg_match('#^/catalog/(.+)$#i', $normalizedPath, $m)) {
            $aliases[] = '/'.$m[1];
        }

        return $aliases;
    }

    /**
     * Активний редирект за нормалізованим шляхом: спочатку точний збіг, потім legacy-аліаси (/locale/catalog/… → без catalog).
     */
    public static function findActiveForNormalizedPath(string $normalizedPath): ?self
    {
        $candidates = array_values(array_unique(array_merge(
            [$normalizedPath],
            self::legacyPathAliasesToTry($normalizedPath)
        )));

        if ($candidates === []) {
            return null;
        }

        $rows = static::query()
            ->where('is_active', true)
            ->whereIn('from_url', $candidates)
            ->get();

        foreach ($candidates as $candidate) {
            $found = $rows->firstWhere('from_url', $candidate);
            if ($found !== null) {
                return $found;
            }
        }

        return null;
    }
}
