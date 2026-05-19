<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kit_sections', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->json('lead')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('kits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kit_section_id')->constrained('kit_sections')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->json('title')->nullable();
            $table->json('tagline')->nullable();
            $table->json('description')->nullable();
            $table->string('catalog_mode', 16)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('kit_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kit_id')->constrained('kits')->cascadeOnDelete();
            $table->json('title')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('kit_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kit_id')->constrained('kits')->cascadeOnDelete();
            $table->foreignId('kit_group_id')->nullable()->constrained('kit_groups')->nullOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $this->migrateFromHomeBlocks();

        if (Schema::hasTable('home_block_kit_lines')) {
            Schema::dropIfExists('home_block_kit_lines');
        }

        if (Schema::hasTable('home_block_kit_groups')) {
            Schema::dropIfExists('home_block_kit_groups');
        }

        if (Schema::hasColumn('home_block_items', 'kit_description')) {
            Schema::table('home_block_items', function (Blueprint $table) {
                $table->dropColumn(['kit_description', 'kits_catalog_mode']);
            });
        }

        DB::table('home_block_items')
            ->whereIn('home_block_id', function ($q) {
                $q->select('id')->from('home_blocks')->where('type', 'kits');
            })
            ->delete();

        DB::table('home_blocks')->where('type', 'kits')->delete();
    }

    public function down(): void
    {
        Schema::dropIfExists('kit_lines');
        Schema::dropIfExists('kit_groups');
        Schema::dropIfExists('kits');
        Schema::dropIfExists('kit_sections');
    }

    private function migrateFromHomeBlocks(): void
    {
        if (! Schema::hasTable('home_blocks')) {
            return;
        }

        $kitBlocks = DB::table('home_blocks')->where('type', 'kits')->orderBy('id')->get();

        if ($kitBlocks->isEmpty()) {
            DB::table('kit_sections')->insert([
                'title' => json_encode(['uk' => '', 'ru' => '', 'en' => ''], JSON_THROW_ON_ERROR),
                'lead' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        $itemIdToKitId = [];

        foreach ($kitBlocks as $block) {
            $sectionId = DB::table('kit_sections')->insertGetId([
                'title' => $block->title,
                'lead' => $block->lead ?? null,
                'is_active' => (bool) $block->is_active,
                'created_at' => $block->created_at ?? now(),
                'updated_at' => $block->updated_at ?? now(),
            ]);

            $items = DB::table('home_block_items')
                ->where('home_block_id', $block->id)
                ->orderBy('sort_order')
                ->get();

            foreach ($items as $item) {
                $kitId = DB::table('kits')->insertGetId([
                    'kit_section_id' => $sectionId,
                    'category_id' => $item->category_id,
                    'title' => $item->custom_title,
                    'tagline' => $item->custom_tagline,
                    'description' => $item->kit_description ?? null,
                    'catalog_mode' => $item->kits_catalog_mode ?? null,
                    'sort_order' => (int) ($item->sort_order ?? 0),
                    'is_active' => true,
                    'created_at' => $item->created_at ?? now(),
                    'updated_at' => $item->updated_at ?? now(),
                ]);

                $itemIdToKitId[(int) $item->id] = $kitId;
            }
        }

        if (! Schema::hasTable('home_block_kit_groups')) {
            return;
        }

        $groupIdMap = [];

        $groups = DB::table('home_block_kit_groups')->orderBy('id')->get();
        foreach ($groups as $group) {
            $kitId = $itemIdToKitId[(int) $group->home_block_item_id] ?? null;
            if ($kitId === null) {
                continue;
            }

            $newGroupId = DB::table('kit_groups')->insertGetId([
                'kit_id' => $kitId,
                'title' => $group->title,
                'sort_order' => (int) ($group->sort_order ?? 0),
                'created_at' => $group->created_at ?? now(),
                'updated_at' => $group->updated_at ?? now(),
            ]);

            $groupIdMap[(int) $group->id] = $newGroupId;
        }

        $lines = DB::table('home_block_kit_lines')->orderBy('id')->get();
        foreach ($lines as $line) {
            $kitId = $itemIdToKitId[(int) $line->home_block_item_id] ?? null;
            if ($kitId === null) {
                continue;
            }

            $oldGroupId = $line->home_block_kit_group_id ? (int) $line->home_block_kit_group_id : null;

            DB::table('kit_lines')->insert([
                'kit_id' => $kitId,
                'kit_group_id' => $oldGroupId ? ($groupIdMap[$oldGroupId] ?? null) : null,
                'product_id' => $line->product_id,
                'sort_order' => (int) ($line->sort_order ?? 0),
                'created_at' => $line->created_at ?? now(),
                'updated_at' => $line->updated_at ?? now(),
            ]);
        }

        if (Schema::hasTable('media')) {
            foreach ($itemIdToKitId as $oldItemId => $newKitId) {
                DB::table('media')
                    ->where('model_type', 'App\\Models\\HomeBlockItem')
                    ->where('model_id', $oldItemId)
                    ->update([
                        'model_type' => 'App\\Models\\Kit',
                        'model_id' => $newKitId,
                    ]);
            }
        }
    }
};
