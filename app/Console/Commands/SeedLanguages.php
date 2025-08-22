<?php

namespace App\Console\Commands;

use App\Models\Language;
use Illuminate\Console\Command;

class SeedLanguages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'languages:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed languages table with default languages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding languages table...');

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
            $lang = Language::updateOrCreate(
                ['id' => $language['id']],
                ['name' => $language['name']]
            );
            
            $this->info("Language {$lang->name} (ID: {$lang->id}) created/updated successfully.");
        }

        $this->info('Languages seeding completed successfully!');
        
        return Command::SUCCESS;
    }
}