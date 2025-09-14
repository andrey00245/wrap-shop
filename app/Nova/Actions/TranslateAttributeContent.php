<?php

namespace App\Nova\Actions;

use App\Services\TranslationService;
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
        $translator = app(TranslationService::class);

        foreach ($models as $attribute) {
            try {
                $originalName = $attribute->name;

                if (!$originalName) {
                    return Action::danger("Відсутні початкові дані для перекладу у атрибуту #{$attribute->id}");
                }

                // Переводим название атрибута
                $attribute->name = [
                    'uk' => $originalName, // Оригинальное название на украинском
                    'ru' => $translator->translate($originalName, 'русский'),
                    'en' => $translator->translate($originalName, 'английский'),
                ];

                $attribute->save();

                dump("✅ Переведен атрибут #{$attribute->id}: {$originalName}");

            } catch (\Exception $e) {
                return Action::danger("Помилка під час перекладу атрибуту #{$attribute->id}: " . $e->getMessage());
            }
        }

        return Action::message('Переклад атрибутів виконано');
    }
}