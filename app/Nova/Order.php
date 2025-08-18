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
use Laravel\Nova\Panel;

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

            Text::make('МійСклад', function () {
                if ($this->moysklad_id) {
                    $url = "https://online.moysklad.ru/app/#customerorder/edit?id={$this->moysklad_id}";
                    return "<a href='{$url}' target='_blank' class='no-underline text-primary-500 hover:underline font-semibold'>Замовлення</a>";
                }
                return '—';
            })->asHtml()->onlyOnIndex(),

			DateTime::make('Створено', 'created_at')->onlyOnDetail(),
			DateTime::make('Оновлено', 'updated_at')->onlyOnDetail(),

            BelongsToMany::make('Товари', 'products', Product::class)
                ->fields(function () {
                    return [
                        Number::make('Кількість', 'quantity')
                            ->step(1)
                            ->min(1)
                            ->rules('required', 'integer', 'min:1'),
                        Number::make('Ціна', 'price')
                            ->step(0.01)
                            ->displayUsing(fn ($value) => number_format((float)$value, 2, '.', ' ') . ' ₴')
                            ->rules('required', 'numeric', 'min:0')
                    ];
                }),

            new Panel('Checkbox (Фіскалізація)', $this->checkboxFields()),
        ];
	}

    protected function checkboxFields()
    {
        return [
            Text::make('ID чека', 'checkbox_receipt_id')
                ->onlyOnDetail(),

            Badge::make('Статус чека', 'checkbox_status')
                ->map([
                    'created'     => 'info',
                    'pending'     => 'warning',
                    'success'     => 'success',
                    'failed'      => 'danger',
                    'not_created' => 'info', // используем info вместо secondary
                ])
                ->labels([
                    'created'     => 'Створений',
                    'pending'     => 'Очікує підтвердження',
                    'success'     => 'Фіскалізовано',
                    'failed'      => 'Помилка',
                    'not_created' => 'Не створений',
                ])
                ->resolveUsing(fn ($value) => $value ?? 'not_created')
                ->onlyOnDetail(),

            Text::make('Відповідь Checkbox', function () {
                if (empty($this->checkbox_response)) {
                    return null; // Nova не будет выводить поле вообще
                }

                return '<pre style="white-space: pre-wrap; font-size: 12px;">'
                    . e(json_encode($this->checkbox_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
                    . '</pre>';
            })->asHtml()->onlyOnDetail()->nullable(),
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
