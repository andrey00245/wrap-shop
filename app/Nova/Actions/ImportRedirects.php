<?php

namespace App\Nova\Actions;

use App\Models\Redirect;
use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;

class ImportRedirects extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Імпорт редиректів (CSV з Excel)';

    public function handle(ActionFields $fields, Collection $models)
    {
        /** @var UploadedFile|null $file */
        $file = $fields->get('file');

        if (! $file instanceof UploadedFile) {
            return Action::danger('Файл не завантажено');
        }

        // Защита от загрузки .xlsx: Excel по умолчанию сохраняет в формате Office (ZIP, "PK...")
        // Нам нужен именно CSV.
        $originalExtension = strtolower($file->getClientOriginalExtension() ?? '');
        if ($originalExtension !== 'csv') {
            return Action::danger('Будь ласка, спочатку збережіть файл як CSV (Файл → Зберегти як → CSV), а потім завантажте його сюди. Формат .xlsx не підтримується.');
        }

        $path = $file->getRealPath();
        if (! $path || ! is_readable($path)) {
            return Action::danger('Не вдалося прочитати файл');
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return Action::danger('Не вдалося відкрити файл');
        }

        $existing = Redirect::query()
            ->get()
            ->keyBy('from_url');

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        $rowIndex = 0;
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $rowIndex++;

            // Пробуем также CSV с запятой как разделителем
            if (count($row) === 1) {
                $row = str_getcsv($row[0], ',');
            }

            if (count($row) < 2) {
                $skipped++;

                continue;
            }

            // Нормализуем кодировку (Excel на Windows часто даёт Windows-1251)
            $fromRaw = $this->normalizeEncoding($row[0] ?? '');
            $toRaw = $this->normalizeEncoding($row[1] ?? '');

            // Пропускаем заголовок
            if ($rowIndex === 1 && stripos($fromRaw, 'old') !== false && stripos($toRaw, 'new') !== false) {
                continue;
            }

            $from = Redirect::normalizePath($fromRaw);
            $to = trim($toRaw);

            if ($from === '' || $to === '') {
                $skipped++;

                continue;
            }

            // Явные саморедиректы
            if ($from === Redirect::normalizePath($to)) {
                $errors[] = "Рядок {$rowIndex}: from_url і to_url співпадають ({$from})";
                $skipped++;

                continue;
            }

            // Антицикли A→B / B→A
            $normalizedTo = Redirect::normalizePath($to);
            if (isset($existing[$normalizedTo]) && $existing[$normalizedTo]->from_url === $from) {
                $errors[] = "Рядок {$rowIndex}: виявлено цикл {$from} ↔ {$normalizedTo}";
                $skipped++;

                continue;
            }

            // Обновляем карту перед проверкой более довгих циклів
            $map = $existing->map(fn (Redirect $r) => $r->to_url)->all();
            $map[$from] = $normalizedTo;

            if ($this->hasCycle($map, $from)) {
                $errors[] = "Рядок {$rowIndex}: імпорт цього редиректу створює цикл ({$from} → ...)";
                $skipped++;

                continue;
            }

            /** @var Redirect|null $model */
            $model = $existing[$from] ?? null;

            if ($model) {
                $model->to_url = $to;
                $model->status_code = 301;
                $model->is_active = true;
                $model->save();
                $updated++;
            } else {
                $model = Redirect::create([
                    'from_url' => $from,
                    'to_url' => $to,
                    'status_code' => 301,
                    'is_active' => true,
                ]);
                $existing[$from] = $model;
                $created++;
            }
        }

        fclose($handle);

        $message = "Імпорт завершено. Створено: {$created}, оновлено: {$updated}, пропущено: {$skipped}.";

        if (! empty($errors)) {
            $message .= ' Помилки: '.implode(' | ', array_slice($errors, 0, 5));
        }

        return Action::message($message);
    }

    /**
     * Приводим строку к коректній UTF‑8, щоб уникнути помилки
     * "Malformed UTF-8 characters, possibly incorrectly encoded".
     */
    protected function normalizeEncoding(string $value): string
    {
        if ($value === '') {
            return $value;
        }

        // Якщо строка вже в UTF‑8 і валідна — залишаємо як є
        if (mb_detect_encoding($value, 'UTF-8', true) && mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        // Пробуем типичные варианты Excel на Windows
        return mb_convert_encoding($value, 'UTF-8', 'Windows-1251,ISO-8859-1,UTF-8');
    }

    /**
     * Проста перевірка на цикли в мапі редиректів.
     *
     * @param  array<string,string>  $map
     */
    protected function hasCycle(array $map, string $start): bool
    {
        $visited = [];
        $current = $start;

        // Обмежуємо довжину ланцюжка, щоб не зациклитись назавжди
        for ($i = 0; $i < 20; $i++) {
            if (! isset($map[$current])) {
                return false;
            }

            $next = $map[$current];

            if (isset($visited[$next])) {
                return true;
            }

            $visited[$next] = true;
            $current = $next;
        }

        return false;
    }

    public function fields(\Laravel\Nova\Http\Requests\NovaRequest $request): array
    {
        return [
            File::make('Файл', 'file')
                ->help('CSV‑файл з Excel. Формат: старий URL;новий URL (перші два стовпці).')
                ->rules('required', 'file'),
        ];
    }
}
