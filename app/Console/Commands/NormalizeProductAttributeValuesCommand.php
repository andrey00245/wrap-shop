<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Support\HomeIndexCache;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class NormalizeProductAttributeValuesCommand extends Command
{
    protected $signature = 'attributes:normalize-values
                            {--field= : field_name атрибута (наприклад type)}
                            {--locale=uk : локаль для групування дублів}
                            {--execute : застосувати зміни (за замовчуванням лише звіт)}';

    protected $description = 'Знайти й уніфікувати значення products_attributes, що відрізняються лише регістром';

    public function handle(): int
    {
        $field = $this->option('field');
        $locale = (string) $this->option('locale');
        $execute = (bool) $this->option('execute');

        $attributes = Attribute::query()
            ->when($field, fn ($q) => $q->where('field_name', $field))
            ->orderBy('id')
            ->get();

        if ($attributes->isEmpty()) {
            $this->warn('Атрибути не знайдено.');

            return self::FAILURE;
        }

        $totalGroups = 0;
        $totalRows = 0;

        foreach ($attributes as $attribute) {
            $rows = ProductAttribute::query()
                ->where('attribute_id', $attribute->id)
                ->get();

            if ($rows->isEmpty()) {
                continue;
            }

            /** @var array<string, array{variants: array<string, int>, rows: Collection<int, ProductAttribute>}> $groups */
            $groups = [];

            foreach ($rows as $row) {
                $display = Product::extractPivotValueForDisplay($row->value, $locale);
                if ($display === null || $display === '') {
                    continue;
                }

                $key = mb_strtolower($display);
                $groups[$key]['variants'][$display] = ($groups[$key]['variants'][$display] ?? 0) + 1;
                $groups[$key]['rows'][] = $row;
            }

            foreach ($groups as $key => $group) {
                if (count($group['variants']) < 2) {
                    continue;
                }

                $maxCount = max($group['variants']);
                $topVariants = array_keys(array_filter(
                    $group['variants'],
                    fn (int $count) => $count === $maxCount
                ));
                $canonical = Product::pickCanonicalAttributeDisplayValue($topVariants);

                $totalGroups++;
                $variantList = collect($group['variants'])
                    ->map(fn ($count, $variant) => "{$variant} ({$count})")
                    ->implode(', ');

                $this->line(sprintf(
                    '[%s] %s → <info>%s</info> (%d товарів)',
                    $attribute->field_name,
                    $variantList,
                    $canonical,
                    count($group['rows'])
                ));

                foreach ($group['rows'] as $row) {
                    $newValue = $this->normalizeRowValue($row->value, $key, $canonical, $locale);
                    if ($newValue === $row->value) {
                        continue;
                    }

                    $totalRows++;

                    if ($execute) {
                        $row->value = $newValue;
                        $row->save();
                    }
                }
            }
        }

        if ($totalGroups === 0) {
            $this->info('Дублів за регістром не знайдено.');

            return self::SUCCESS;
        }

        if ($execute) {
            HomeIndexCache::flush();
            $this->info("Оновлено записів: {$totalRows} (груп: {$totalGroups}).");
        } else {
            $this->warn("Знайдено груп: {$totalGroups}, записів до оновлення: {$totalRows}. Запустіть з --execute для застосування.");
        }

        return self::SUCCESS;
    }

    /**
     * @return array<string, mixed>|string
     */
    private function normalizeRowValue(mixed $value, string $groupKey, string $canonical, string $locale): mixed
    {
        if (is_array($value)) {
            $updated = $value;
            foreach ($updated as $loc => $text) {
                if (! is_scalar($text)) {
                    continue;
                }
                $trimmed = trim((string) $text);
                if ($trimmed !== '' && mb_strtolower($trimmed) === $groupKey) {
                    $updated[$loc] = $canonical;
                }
            }

            return $updated;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $this->normalizeRowValue($decoded, $groupKey, $canonical, $locale);
            }

            $trimmed = trim($value);

            return mb_strtolower($trimmed) === $groupKey ? $canonical : $value;
        }

        return $value;
    }
}
