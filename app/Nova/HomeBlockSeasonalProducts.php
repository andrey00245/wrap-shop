<?php

namespace App\Nova;

use App\Enums\HomeBlockType;

class HomeBlockSeasonalProducts extends AbstractTypedHomeBlockResource
{
    public static $model = \App\Models\HomeBlock::class;

    public static $title = 'title_for_nova';

    public static $search = [
        'id',
    ];

    protected static function homeBlockType(): HomeBlockType
    {
        return HomeBlockType::Products;
    }

    public static function label(): string
    {
        return 'Сезонні товари на головній';
    }

    public static function singularLabel(): string
    {
        return 'Блок сезонних товарів';
    }

    public static function uriKey(): string
    {
        return 'home-block-seasonal-products';
    }
}
