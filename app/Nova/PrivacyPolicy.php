<?php

namespace App\Nova;

use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;

class PrivacyPolicy extends Resource
{
  /**
   * The model the resource corresponds to.
   *
   * @var class-string<\App\Models\PrivacyPolicy>
   */
  public static $model = \App\Models\PrivacyPolicy::class;

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


  public static function label()
  {
    return 'Політика конфіденційності';
  }

  /**
   * Get the fields displayed by the resource.
   *
   * @param \Laravel\Nova\Http\Requests\NovaRequest $request
   * @return array
   */
  public function fields(NovaRequest $request)
  {
    return [
      ID::make()->sortable(),

      NovaTabTranslatable::make([
        Text::make('Заголовок H1', 'h1'),
        Text::make('Мета тайтл', 'meta_title'),
        Text::make('Мета дескрипшн', 'meta_description'),
        CkEditor::make('Контент', 'content')
          ->stacked()
          ->fullWidth(),
      ]),

    ];
  }


  /**
   * Get the cards available for the request.
   *
   * @param \Laravel\Nova\Http\Requests\NovaRequest $request
   * @return array
   */
  public function cards(NovaRequest $request)
  {
    return [];
  }

  /**
   * Get the filters available for the resource.
   *
   * @param \Laravel\Nova\Http\Requests\NovaRequest $request
   * @return array
   */
  public function filters(NovaRequest $request)
  {
    return [];
  }

  /**
   * Get the lenses available for the resource.
   *
   * @param \Laravel\Nova\Http\Requests\NovaRequest $request
   * @return array
   */
  public function lenses(NovaRequest $request)
  {
    return [];
  }

  /**
   * Get the actions available for the resource.
   *
   * @param \Laravel\Nova\Http\Requests\NovaRequest $request
   * @return array
   */
  public function actions(NovaRequest $request)
  {
    return [];
  }
}
