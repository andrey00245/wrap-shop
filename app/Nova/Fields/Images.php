<?php

namespace App\Nova\Fields;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images as BaseImages;
use Illuminate\Contracts\Validation\Rule;

/**
 * Поля зображень у Nova з єдиним лімітом {@see config('nova-media-library.max_upload_kb')} (за замовчуванням 15 МБ).
 */
class Images extends BaseImages
{
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $maxKb = (int) config('nova-media-library.max_upload_kb', 15360);
        $this->singleImageRules(['max:'.$maxKb]);
        $this->setMaxFileSize($maxKb);
    }

    public function singleMediaRules($rules): self
    {
        $maxKb = (int) config('nova-media-library.max_upload_kb', 15360);
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
        $maxKb = (int) config('nova-media-library.max_upload_kb', 15360);
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
}
