<?php

namespace App\Nova;

use App\Enums\HomeBlockType;

class HomeBlockBanners extends AbstractTypedHomeBlockResource
{
    public static $model = \App\Models\HomeBlock::class;

    public static $title = 'title_for_nova';

    public static $search = [
        'id',
    ];

    protected static function homeBlockType(): HomeBlockType
    {
        return HomeBlockType::Banner;
    }

    public static function label(): string
    {
        return 'Банери на головній';
    }

    public static function singularLabel(): string
    {
        return 'Блок банерів';
    }

    public static function uriKey(): string
    {
        return 'home-block-banners';
    }
}
