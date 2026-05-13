<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class StorageLinkCommand extends Command
{
    protected $signature = 'storage:link-quick {--force : Принудительно пересоздать ссылку}';
    protected $description = 'Быстрое создание storage symlink с проверками';

    public function handle()
    {
        $this->info("🔗 Создание storage symlink...");

        $publicStoragePath = public_path('storage');
        $storageAppPath = storage_path('app/public');

        // Проверяем, существует ли storage/app/public
        if (!File::exists($storageAppPath)) {
            $this->error("❌ Папка storage/app/public не существует!");
            $this->info("Создаем папку...");
            File::makeDirectory($storageAppPath, 0755, true);
            $this->info("✅ Папка storage/app/public создана");
        }

        // Проверяем, существует ли уже ссылка
        if (File::exists($publicStoragePath)) {
            if ($this->option('force')) {
                $this->warn("🗑️ Удаляем существующую ссылку...");
                if (is_link($publicStoragePath)) {
                    unlink($publicStoragePath);
                } else {
                    File::deleteDirectory($publicStoragePath);
                }
                $this->info("✅ Старая ссылка удалена");
            } else {
                $this->warn("⚠️ Ссылка уже существует. Используйте --force для пересоздания");
                return 0;
            }
        }

        try {
            // Создаем ссылку
            Artisan::call('storage:link');
            $output = Artisan::output();

            if (File::exists($publicStoragePath)) {
                $this->info("✅ Storage symlink успешно создан!");
                $this->line("📁 Ссылка: {$publicStoragePath} -> {$storageAppPath}");
                
                // Проверяем, что ссылка работает
                if (File::isLink($publicStoragePath)) {
                    $this->info("🔗 Ссылка активна и работает");
                } else {
                    $this->warn("⚠️ Ссылка создана, но может не работать корректно");
                }
            } else {
                $this->error("❌ Не удалось создать ссылку");
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("❌ Ошибка при создании ссылки: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}