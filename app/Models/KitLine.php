<?php

namespace App\Models;

use App\Support\HomeIndexCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class KitLine extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    protected $fillable = [
        'kit_id',
        'kit_group_id',
        'product_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(static function (KitLine $line) {
            static::ensureKitId($line);
        });

        static::saving(static function (KitLine $line) {
            static::ensureKitId($line);

            if ($line->kit_group_id !== null && $line->kit_id !== null) {
                $group = KitGroup::query()->find($line->kit_group_id);
                if ($group === null || (int) $group->kit_id !== (int) $line->kit_id) {
                    $line->kit_group_id = null;
                }
            }
        });

        static::saved(static function () {
            HomeIndexCache::flush();
        });

        static::deleted(static function () {
            HomeIndexCache::flush();
        });
    }

    public static function ensureKitId(KitLine $line): void
    {
        if (filled($line->kit_group_id) && blank($line->kit_id)) {
            $kitId = KitGroup::query()->whereKey($line->kit_group_id)->value('kit_id');
            if ($kitId) {
                $line->kit_id = (int) $kitId;
            }
        }

        if (blank($line->kit_id)) {
            static::applyParentIdsFromNovaRequest($line);
        }
    }

    public static function applyParentIdsFromNovaRequest(KitLine $line): void
    {
        $request = request();
        if (! $request instanceof NovaRequest) {
            return;
        }

        if (! filled($request->viaResource) || ! filled($request->viaResourceId)) {
            return;
        }

        if ($request->viaResource === 'kit-groups' && $request->viaRelationship === 'kitLines') {
            $line->kit_group_id = (int) $request->viaResourceId;
            $line->kit_id = (int) (KitGroup::query()->whereKey($line->kit_group_id)->value('kit_id') ?? 0);

            return;
        }

        if ($request->viaResource === 'kits' && $request->viaRelationship === 'ungroupedKitLines') {
            $line->kit_id = (int) $request->viaResourceId;
            $line->kit_group_id = null;
        }
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('kit_id', $this->kit_id);
    }

    public function kit(): BelongsTo
    {
        return $this->belongsTo(Kit::class);
    }

    public function kitGroup(): BelongsTo
    {
        return $this->belongsTo(KitGroup::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getProductDisplayNameAttribute(): string
    {
        return $this->product?->getName() ?? 'Товар #'.$this->id;
    }
}
