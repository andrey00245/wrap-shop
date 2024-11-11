<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Fields\Boolean;
use Mostafaznv\NovaVideo\Video as VideoField;
use Mostafaznv\NovaVideo\Enums\NovaVideoMode;
use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;

class VideoReview extends Resource
{
    use TranslatableTabToRowTrait;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Video>
     */
    public static $model = \App\Models\VideoReview::class;

    public static $title = 'title';

    public static $search = [
        'title',
    ];

    public static function label()
    {
        return 'Відео Огняд';
    }

    public function fields(Request $request)
    {
        return [
            NovaTabTranslatable::make([
                Text::make('Назва', 'title')
            ])->hideFromIndex(),

            BelongsTo::make('Категорія','category',VideoCategory::class),
            Images::make('Фото','image'),
            VideoField::make('Відео', 'video'),
            Boolean::make('Активний','is_active'),
        ];
    }
}
