<?php

namespace App\Nova\Actions;

use App\Models\HomeBlockItem;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class SyncHomeBlockItemChildQuickLinks extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Додати дочірні категорії як посилання';

    public function handle(ActionFields $fields, Collection $models)
    {
        $added = 0;

        /** @var HomeBlockItem $item */
        foreach ($models as $item) {
            $parent = $item->category;
            if ($parent === null) {
                continue;
            }

            $existing = $item->quickLinks()->pluck('category_id')->all();
            $order = (int) ($item->quickLinks()->max('sort_order') ?? -1);

            foreach ($parent->children()->orderBy('id')->get() as $child) {
                if (in_array($child->id, $existing, true)) {
                    continue;
                }
                $order++;
                $item->quickLinks()->create([
                    'category_id' => $child->id,
                    'sort_order' => $order,
                ]);
                $existing[] = $child->id;
                $added++;
            }
        }

        if ($added === 0) {
            return Action::message('Нічого не додано: немає дочірніх категорій або вони вже додані.');
        }

        return Action::message('Додано посилань: '.$added);
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
