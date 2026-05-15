<?php

namespace App\Nova;

use App\Enums\HomeBlockType;

class HomeBlockKits extends AbstractTypedHomeBlockResource
{
    public static $model = \App\Models\HomeBlock::class;

    public static $title = 'title_for_nova';

    public static $search = [
        'id',
    ];

    protected static function homeBlockType(): HomeBlockType
    {
        return HomeBlockType::Kits;
    }

    public static function label(): string
    {
        return 'Набори (kits)';
    }

    public static function singularLabel(): string
    {
        return 'Блок наборів';
    }

    public static function uriKey(): string
    {
        return 'home-block-kits';
    }
}
