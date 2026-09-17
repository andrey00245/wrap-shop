<?php

namespace App\Nova\Actions;

use App\Services\ContentTranslationService;
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
        $translator = app(ContentTranslationService::class);

        foreach ($models as $product) {
            try {
                $translator->translateOne($product);
            } catch (\Exception $e) {
                return Action::danger("Помилка під час перекладу продукту #{$product->id}: ".$e->getMessage());
            }
        }

        return Action::message('Переклад виконано');
    }
}
