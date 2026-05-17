<?php

namespace App\Support;

use App\Models\Faq;

final class SiteFaq
{
    public static function hasActiveItems(): bool
    {
        static $has = null;

        if ($has === null) {
            $has = Faq::query()->where('is_active', true)->exists();
        }

        return $has;
    }
}
