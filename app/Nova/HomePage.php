<?php

namespace App\Nova;

use App\Nova\Fields\NovaTabTranslatable;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;

class HomePage extends Resource
{
    /**
     * @var class-string<\App\Models\HomePage>
     */
    public static $model = \App\Models\HomePage::class;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    public static function label()
    {
        return 'Головна';
    }

    public static function singularLabel()
    {
        return 'Головна';
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            NovaTabTranslatable::make([
                CkEditor::make('Контент', 'content')
                    ->help('Основний контент / опис головної сторінки')
                    ->stacked()
                    ->fullWidth()
                    ->hideFromIndex(),
                CkEditor::make('SEO текст', 'seo_text')
                    ->help('Додатковий SEO текст на головній')
                    ->stacked()
                    ->fullWidth()
                    ->hideFromIndex(),
            ])->setTitle('Контент'),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }
}
