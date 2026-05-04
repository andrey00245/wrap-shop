<?php

namespace App\Nova\Actions;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ApproveReviewAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Схвалити відгуки';

    public function handle(ActionFields $fields, Collection $models)
    {
        /** @var Review $review */
        foreach ($models as $review) {
            $review->update([
                'moderation_status' => 'approved',
                'is_active' => true,
                'moderated_at' => now(),
            ]);
        }

        return Action::message('Відгуки схвалено та опубліковано.');
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
