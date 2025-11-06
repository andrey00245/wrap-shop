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

                // Переводим название атрибута, но если это брендоподобное имя — не трогаем (редкий кейс)
                if ($this->shouldSkipTranslation($originalName)) {
                    $attribute->name = [
                        'uk' => $originalName,
                        'ru' => $originalName,
                        'en' => $originalName,
                    ];
                } else {
                    $attribute->name = [
                        'uk' => $originalName,
                        'ru' => $translator->translate($originalName, 'русский'),
                        'en' => $translator->translate($originalName, 'английский'),
                    ];
                }

                $attribute->save();

                dump("✅ Переведен атрибут #{$attribute->id}: {$originalName}");

            } catch (\Exception $e) {
                return Action::danger("Помилка під час перекладу атрибуту #{$attribute->id}: " . $e->getMessage());
            }
        }

        return Action::message('Переклад атрибутів виконано');
    }

    private function shouldSkipTranslation($text): bool
    {
        if (!is_string($text)) return true;
        $trimmed = trim($text);
        if ($trimmed === '') return true;
        if (preg_match('/^[A-Za-z0-9 .\-\_\+\&\/#]+$/u', $trimmed)) {
            return true;
        }
        if (preg_match('/^[A-Z0-9\-\_]+$/', $trimmed)) {
            return true;
        }
        return false;
    }
}