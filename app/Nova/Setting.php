<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Spatie\Translatable\HasTranslations;

class Setting extends Resource
{
    use HasTranslations;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Setting>
     */
    public static $model = \App\Models\Setting::class;

    public static function label()
    {
        return 'Налаштування';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function authorizedToCreate(Request $request) : bool
    {
        return false;
    }

    public function authorizedToDelete(Request $request) : bool
    {
        return false;
    }

    public function authorizedToReplicate(Request $request) : bool
    {
        return false;
    }

    protected function contactsFields(){
        return[
            Text::make('Номер телефону', 'phone')->sortable(),
            Text::make('Номер телефону для відображення', 'phone_view')->sortable()->hideFromIndex(),
            Text::make('Додатковий номер телефону', 'phone_aditional')->sortable()->hideFromIndex(),
            Text::make('Додатковий номер телефону для відображення', 'phone_aditional_view')->sortable()->hideFromIndex(),
            Text::make('Посилання на телеграм', 'telegram')->sortable()->hideFromIndex(),
            Text::make('Посилання на інстаграм', 'instagram')->sortable()->hideFromIndex(),
            Text::make('Email', 'email')->sortable()->hideFromIndex()
        ];
    }
    protected function addressFields(){
        return[
            NovaTabTranslatable::make([
                Text::make('Адреса', 'address')->sortable(),
            ])->hideFromIndex(),
            Text::make('Email', 'email')->sortable()->hideFromIndex(),
        ];
    }
    protected function videoBanerFields(){
        return[
            NovaTabTranslatable::make([
                Text::make('Заголовок', 'video_banner_title')->sortable(),
                Textarea::make('Контент', 'video_banner_desc'),
            ])->hideFromIndex(),
        ];
    }
    protected function sloganFields(){
        return[
            NovaTabTranslatable::make([
                Text::make('Заголовок', 'slogan_title')->sortable(),
                Textarea::make('Контент', 'slogan_desc')->hideFromIndex(),
            ])->hideFromIndex(),
        ];
    }

    protected function currency(){
        return[
            Number::make('Курс $', 'currency')->step(0.01)->sortable(),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('-', function (){
                return 'Налаштування';
            })->onlyOnIndex(),
            new Panel('Контакти', $this->contactsFields()),
            new Panel('Aдреси', $this->addressFields()),
            new Panel('Відеобанер', $this->videoBanerFields()),
            new Panel('Слоган', $this->sloganFields()),
            new Panel('Валюта', $this->currency()),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
        ];
    }
}
