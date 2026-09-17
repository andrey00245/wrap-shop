<?php

namespace App\Nova\Actions;

use App\Services\ContentTranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class TranslateAttributeContent extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Перекласти атрибути';

    public function handle(ActionFields $fields, Collection $models)
    {
        $translator = app(ContentTranslationService::class);

        foreach ($models as $attribute) {
            try {
                $translator->translateOne($attribute);
            } catch (\Exception $e) {
                return Action::danger("Помилка під час перекладу атрибуту #{$attribute->id}: ".$e->getMessage());
            }
        }

        return Action::message('Переклад атрибутів виконано');
    }
}
