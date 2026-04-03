<?php

namespace Database\Seeders;

use App\Models\BlogAuthor;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = BlogAuthor::query()->firstOrCreate(
            ['slug' => 'wrap-shop-team'],
            [
                'name' => ['uk' => 'Wrap.Shop Team', 'ru' => 'Wrap.Shop Team', 'en' => 'Wrap.Shop Team'],
                'role' => ['uk' => 'Редакція', 'ru' => 'Редакция', 'en' => 'Editorial'],
                'bio' => [
                    'uk' => 'Пишемо про плівки, детейлінг, тюнінг та інструменти — коротко, практично і з прикладами з майстерні.',
                    'ru' => 'Пишем о плёнках, детейлинге, тюнинге и инструментах.',
                    'en' => 'We write about wraps, detailing, tuning and tools.',
                ],
                'avatar_url' => null,
                'socials' => [
                    'instagram' => 'https://www.instagram.com/wrap.shop.ua/',
                    'telegram' => 'https://t.me/wrap_shop_ua',
                ],
                'is_active' => true,
            ]
        );

        $categories = [
            ['uk' => 'Плівка для авто', 'sort' => 10],
            ['uk' => 'Шумоізоляція та віброізоляція', 'sort' => 20],
            ['uk' => 'Детейлінг', 'sort' => 30],
            ['uk' => 'Тюнінг', 'sort' => 40],
            ['uk' => 'Загальні поради та огляди', 'sort' => 50],
        ];

        foreach ($categories as $row) {
            $nameUk = $row['uk'];
            BlogCategory::query()->firstOrCreate(
                ['name->uk' => $nameUk],
                [
                    'name' => [
                        'uk' => $nameUk,
                        'ru' => $nameUk,
                        'en' => $nameUk,
                    ],
                    'sort_order' => $row['sort'],
                    'is_active' => true,
                ]
            );
        }

        $this->command?->info('Blog author id='.$author->id.' and categories synced. Додайте статті в Nova (Блог — статті).');
    }
}
