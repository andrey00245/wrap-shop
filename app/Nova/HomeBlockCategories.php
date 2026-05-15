<?php

namespace App\Nova;

use App\Enums\HomeBlockType;

class HomeBlockCategories extends AbstractTypedHomeBlockResource
{
    public static $model = \App\Models\HomeBlock::class;

    public static $title = 'title_for_nova';

    public static $search = [
        'id',
    ];

    protected static function homeBlockType(): HomeBlockType
    {
        return HomeBlockType::Categories;
    }

    public static function label(): string
    {
        return 'Категорії на головній';
    }

    public static function singularLabel(): string
    {
        return 'Блок категорій';
    }

    public static function uriKey(): string
    {
        return 'home-block-categories';
    }
}
