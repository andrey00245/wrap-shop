<?php

namespace App\Nova\Fields;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images as BaseImages;
use Illuminate\Contracts\Validation\Rule;

/**
 * Поля зображень у Nova з єдиним лімітом {@see config('nova-media-library.max_upload_kb')} (за замовчуванням 15 МБ).
 */
class Images extends BaseImages
{
    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $maxKb = $this->effectiveMaxUploadKb();
        $this->singleImageRules(['max:'.$maxKb]);
    }

    public function singleMediaRules($rules): self
    {
        $maxKb = $this->effectiveMaxUploadKb();
        $maxRule = 'max:'.$maxKb;

        if ($rules instanceof Rule) {
            return parent::singleMediaRules([$rules, $maxRule]);
        }

        $merged = is_array($rules) ? $rules : func_get_args();

        foreach ($merged as $rule) {
            if (is_string($rule) && str_starts_with($rule, 'max:')) {
                return parent::singleMediaRules($merged);
            }
        }

        $merged[] = $maxRule;

        return parent::singleMediaRules($merged);
    }

    public function singleImageRules($singleImageRules): self
    {
        $maxKb = $this->effectiveMaxUploadKb();
        $maxRule = 'max:'.$maxKb;

        if ($singleImageRules instanceof Rule) {
            return parent::singleImageRules([$singleImageRules, $maxRule]);
        }

        $merged = is_array($singleImageRules) ? $singleImageRules : func_get_args();

        foreach ($merged as $rule) {
            if (is_string($rule) && str_starts_with($rule, 'max:')) {
                return parent::singleImageRules($merged);
            }
        }

        $merged[] = $maxRule;

        return parent::singleImageRules($merged);
    }

    protected function effectiveMaxUploadKb(): int
    {
        $configuredKb = (int) config('nova-media-library.max_upload_kb', 15360);
        $uploadIniKb = $this->iniSizeToKb((string) ini_get('upload_max_filesize'));
        $postIniKb = $this->iniSizeToKb((string) ini_get('post_max_size'));

        $limits = array_filter([$configuredKb, $uploadIniKb, $postIniKb], static fn ($value) => $value > 0);

        return empty($limits) ? $configuredKb : min($limits);
    }

    protected function iniSizeToKb(string $value): int
    {
        $value = trim($value);
        if ($value === '') {
            return 0;
        }

        if (!preg_match('/^(\d+(?:\.\d+)?)\s*([KMG]?)/i', $value, $matches)) {
            return 0;
        }

        $number = (float) $matches[1];
        $unit = strtoupper($matches[2] ?? '');

        return match ($unit) {
            'G' => (int) round($number * 1024 * 1024),
            'M' => (int) round($number * 1024),
            'K' => (int) round($number),
            default => (int) round($number / 1024),
        };
    }
}
