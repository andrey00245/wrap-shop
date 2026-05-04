<?php

namespace App\Nova\Actions;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class RejectReviewAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Відхилити відгуки';

    public function handle(ActionFields $fields, Collection $models)
    {
        /** @var Review $review */
        foreach ($models as $review) {
            $review->update([
                'moderation_status' => 'rejected',
                'is_active' => false,
                'moderated_at' => now(),
            ]);
        }

        return Action::message('Відгуки відхилено.');
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
