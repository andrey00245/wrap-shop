<?php

namespace App\Nova;


use App\Models\Product;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use WesselPerik\StatusField\StatusField;

class FastOrder extends Resource
{
    public static $model = 'App\\Models\\FastOrder';

    public static $title = 'id';
    public static $search = ['id', 'name', 'phone', 'email'];

    public static function label()
    {
        return 'Швидке замовлення';
    }

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Product')
                ->sortable()
                ->readonly()
                ->searchable(),

            BelongsTo::make('User')
                ->nullable()
                ->searchable()
                ->sortable(),

            Number::make('Quantity')
                ->sortable()
                ->readonly()
                ->step(0.1)
                ->min(0)
                ->required(),

            Number::make('Total Price')
                ->sortable()
                ->readonly()
                ->step(0.01)
                ->min(0)
                ->required()
                ->default(0),

            Text::make('Name')
                ->sortable()
                ->required(),

            Text::make('Phone')
                ->sortable()
                ->required(),

            Text::make('Email')
                ->sortable()
                ->nullable(),

            Textarea::make('Comment')
                ->nullable(),

            StatusField::make('Status')
                ->sortable()
                ->icons([
                    'clock' => $this->status == 'new',
                    'check-circle' => $this->status == 'completed',
                    'refresh' => $this->status == 'in_progress',
                ])
                ->tooltip([
                    'clock' => 'Нове замовлення',
                    'check-circle' => 'Завершено',
                    'refresh' => 'В процесі',
                ])
                ->info([
                    'clock' => 'Це нове замовлення, яке чекає на обробку.',
                    'check-circle' => 'Це замовлення завершено.',
                    'refresh' => 'Це замовлення в процесі обробки.',
                ])
                ->color([
                    'clock' => 'blue-500',
                    'check-circle' => 'green-500',
                    'refresh' => 'yellow-500',  // Цвет для статуса в процессе
                ])
                ->exceptOnForms(),

             Select::make('Status')
                 ->options([
                     'new' => 'Новий',
                     'in_progress' => 'В процесі',
                     'completed' => 'Завершено',
                 ])
                 ->onlyOnForms()
                 ->rules('required')
        ];
    }

    public static function indexQuery(Request $request, $query)
    {
        if ($status = $request->get('status')) {
            return $query->where('status', $status);
        }

        return $query;
    }
}
