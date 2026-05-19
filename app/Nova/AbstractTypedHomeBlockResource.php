<?php

namespace App\Nova;

use App\Enums\HomeBlockType;
use App\Models\HomeBlock as HomeBlockModel;
use App\Nova\Fields\NovaTabTranslatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

abstract class AbstractTypedHomeBlockResource extends Resource
{
    abstract protected static function homeBlockType(): HomeBlockType;

    /**
     * Один блок на тип на головній — після першого запису кнопку «Створити» прибираємо.
     */
    public static function authorizedToCreate(Request $request): bool
    {
        if (! parent::authorizedToCreate($request)) {
            return false;
        }

        /** @var class-string<HomeBlockModel> $modelClass */
        $modelClass = static::$model;

        return ! $modelClass::query()
            ->where('type', static::homeBlockType()->value)
            ->exists();
    }

    public function authorizedToReplicate(Request $request): bool
    {
        return false;
    }

    public function fields(NovaRequest $request): array
    {
        $tabFields = [
            Text::make('Заголовок секції', 'title')
                ->help('Можна HTML для акценту, напр. &lt;span class="colord"&gt;Категорії&lt;/span&gt; товарів'),
        ];

        return [
            ID::make()->sortable(),

            Text::make('Заголовок', function () {
                $raw = $this->resource->getTranslation('title', 'uk')
                    ?: $this->resource->getTranslation('title', app()->getLocale());
                $plain = trim(strip_tags((string) $raw));

                return $plain !== '' ? $plain : '—';
            })->onlyOnIndex(),

            NovaTabTranslatable::make($tabFields)
                ->setTitle('Заголовок')
                ->hideFromIndex(),

            Hidden::make('type')->default(static::homeBlockType()->value),

            Boolean::make('Активний', 'is_active'),

            HasMany::make('Елементи', 'items', HomeBlockItem::class),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return parent::indexQuery($request, $query)
            ->where('type', static::homeBlockType()->value);
    }

    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query)
            ->where('type', static::homeBlockType()->value);
    }

    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query)
            ->where('type', static::homeBlockType()->value);
    }

    public static function afterCreate(NovaRequest $request, Model $model): void
    {
        static::ensureHomeBlockType($model);
    }

    public static function afterUpdate(NovaRequest $request, Model $model): void
    {
        static::ensureHomeBlockType($model);
    }

    protected static function ensureHomeBlockType(Model $model): void
    {
        if (! $model instanceof HomeBlockModel) {
            return;
        }

        $expected = static::homeBlockType();
        if ($model->getTypeEnum() !== $expected) {
            $model->forceFill(['type' => $expected])->saveQuietly();
        }
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
