<?php

namespace App\Nova\Actions;

use App\Services\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class TranslateProductContent extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Перекласти';

    public function handle(ActionFields $fields, Collection $models)
    {
        $translator = app(TranslationService::class);

        foreach ($models as $product) {
            try {
                $originalName = $product->name;
                $originalDescription = $product->descriptions;


                if (!$originalName || !$originalDescription) {
                    return Action::danger("Відсутні початкові дані для перекладу у продукту #{$product->id}");
                }

                $product->name = [
                    'ru' => $translator->translate($originalName, 'русский'),
                    'en' => $translator->translate($originalName, 'английский'),
                ];

                $product->descriptions = [
                    'ru' => $translator->translate($originalDescription, 'русский'),
                    'en' => $translator->translate($originalDescription, 'английский'),
                ];

                $product->save();
            } catch (\Exception $e) {
                return Action::danger("Помилка під час перекладу продукту #{$product->id}: " . $e->getMessage());
            }
        }

        return Action::message('Переклад виконано');
    }
}
