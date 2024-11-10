<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\Text;

class VideoCategory extends Resource
{
    use TranslatableTabToRowTrait;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\VideoCategory>
     */
    public static $model = \App\Models\VideoCategory::class;

    public static $title = 'name';

    public static $search = [
        'name',
    ];

    public static function label()
    {
        return 'Категорія';
    }

    public function fields(Request $request)
    {
        return [
            NovaTabTranslatable::make([
                Text::make('Назва', 'name')
            ])->hideFromIndex(),
        ];
    }
}
