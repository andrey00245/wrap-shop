<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'id' => Language::LANGUAGE_ID_UK,
                'name' => Language::LANGUAGE_UK,
            ],
            [
                'id' => Language::LANGUAGE_ID_EN,
                'name' => Language::LANGUAGE_EN,
            ],
            [
                'id' => Language::LANGUAGE_ID_RU,
                'name' => Language::LANGUAGE_RU,
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['id' => $language['id']],
                ['name' => $language['name']]
            );
        }
    }
}