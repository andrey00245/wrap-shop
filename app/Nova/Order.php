<?php

namespace App\Nova;

use App\Models\Order as OrderModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\Order>
	 */
	public static $model = \App\Models\Order::class;

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
		'id', 'first_name', 'last_name', 'email', 'phone'
	];

	public static function label()
	{
		return 'Замовлення';
	}

	public static function singularLabel()
	{
		return 'Замовлення';
	}

	/**
	 * Get the fields displayed by the resource.
	 */
	public function fields(NovaRequest $request)
	{
		return [
			ID::make()->sortable(),

			BelongsTo::make('Користувач', 'user', User::class)
				->nullable()
				->showCreateRelationButton(false),

			Text::make('Телефон', 'phone')->sortable()->rules('required', 'string', 'max:255'),
			Text::make('Імʼя', 'first_name')->sortable()->rules('required', 'string', 'max:255'),
			Text::make('Прізвище', 'last_name')->sortable()->rules('required', 'string', 'max:255'),
			Text::make('Email', 'email')->sortable()->rules('required', 'email', 'max:255')->hideFromIndex(),

			Select::make('Доставка', 'shipping_method')->options([
				'novaposhta' => 'Нова Пошта',
				'courier' => 'Курʼєр',
				'pickup' => 'Самовивіз',
			])->displayUsingLabels()->sortable(),
			Text::make('Адреса доставки', 'shipping_address')->hideFromIndex(),
			Text::make('Місто', 'city')->hideFromIndex(),

			Select::make('Оплата', 'payment_method')->options([
				'card' => 'Карта',
				'cod' => 'Накладений платіж',
				'bank' => 'Банківський переказ',
			])->displayUsingLabels()->sortable(),

			Badge::make('Статус', 'status')->map([
				'new' => 'info',
				'pending' => 'info',
				'processing' => 'warning',
				'paid' => 'success',
				'completed' => 'success',
				'canceled' => 'danger',
				'failed' => 'danger',
				'refunded' => 'warning',
			])->sortable(),

            Text::make('Сума', fn () => number_format((float) $this->total, 2, '.', ' ') . ' ₴')
                ->sortable(),

			Text::make('Коментар', 'comment')->onlyOnDetail(),

			DateTime::make('Створено', 'created_at')->onlyOnDetail(),
			DateTime::make('Оновлено', 'updated_at')->onlyOnDetail(),

            BelongsToMany::make('Товари', 'products', Product::class)
                ->fields(function () {
                    return [
                        Number::make('Кількість', 'quantity')
                            ->step(1)
                            ->min(1)
                            ->rules('required', 'integer', 'min:1'),
                        Text::make('Ціна', fn ($value) => number_format((float) $value, 2, '.', ' ') . ' ₴')
                    ];
                })
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
