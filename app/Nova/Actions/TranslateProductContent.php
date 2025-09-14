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

//                // Переводим основные поля продукта
//                $product->name = [
//                    'ru' => $translator->translate($originalName, 'русский'),
//                    'en' => $translator->translate($originalName, 'английский'),
//                ];
//
//                $product->descriptions = [
//                    'ru' => $translator->translate($originalDescription, 'русский'),
//                    'en' => $translator->translate($originalDescription, 'английский'),
//                ];

                $product->save();

                // Переводим атрибуты продукта
                $this->translateProductAttributes($product, $translator);

            } catch (\Exception $e) {
                return Action::danger("Помилка під час перекладу продукту #{$product->id}: " . $e->getMessage());
            }
        }

        return Action::message('Переклад виконано');
    }

    private function translateProductAttributes($product, $translator)
    {
        $productAttributes = $product->products_attributes()->with('attribute')->get();

        foreach ($productAttributes as $productAttribute) {
            try {
                $originalValue = $productAttribute->value;

                // Проверяем, что значение не пустое и не является массивом
                if (empty($originalValue) || is_array($originalValue)) {
                    continue;
                }


                // Переводим значение атрибута
                $translatedValue = [
                    'uk' => $originalValue, // Оригинальное значение на украинском
                    'ru' => $translator->translate($originalValue, 'русский'),
                    'en' => $translator->translate($originalValue, 'английский'),
                ];


                // Обновляем значение атрибута через pivot таблицу
                $updated = $product->attributes()->updateExistingPivot($productAttribute->attribute_id, [
                    'value' => $translatedValue
                ]);

                if ($updated) {
                    dump("✅ Updated attribute {$productAttribute->attribute_id} with value: " . json_encode($translatedValue));
                    
                    // Проверяем, что данные действительно сохранились
                    $product->refresh();
                    $updatedAttribute = $product->attributes()->where('attribute_id', $productAttribute->attribute_id)->first();
                    if ($updatedAttribute) {
                        dump("🔍 Verification - saved value: " . json_encode($updatedAttribute->pivot->value));
                    }
                } else {
                    dump("❌ Failed to update attribute {$productAttribute->attribute_id}");
                }

            } catch (\Exception $e) {
                // Логируем ошибку, но продолжаем с другими атрибутами
                \Log::warning("Ошибка перевода атрибута {$productAttribute->id}: " . $e->getMessage());
            }
        }
    }
}
