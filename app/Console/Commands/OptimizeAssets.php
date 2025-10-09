<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class OptimizeAssets extends Command
{
    protected $signature = 'optimize:assets';
    protected $description = 'Optimize all assets for production';

    public function handle()
    {
        $this->info('🚀 Starting asset optimization...');

        // 1. Build assets with production optimizations
        $this->info('📦 Building assets...');
        Artisan::call('mix:build', ['--production' => true]);
        $this->info('✅ Assets built successfully');

        // 2. Clear and rebuild caches
        $this->info('🗑️ Clearing caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        $this->info('✅ Caches cleared');

        // 3. Optimize configuration
        $this->info('⚙️ Optimizing configuration...');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        $this->info('✅ Configuration optimized');

        // 4. Generate sitemap
        $this->info('🗺️ Generating sitemap...');
        Artisan::call('generate:sitemap');
        $this->info('✅ Sitemap generated');

        // 5. Update robots.txt
        $this->info('🤖 Updating robots.txt...');
        Artisan::call('cache:forget', ['key' => 'robots.txt']);
        $this->info('✅ Robots.txt updated');

        // 5. Check if we need to install additional packages
        $this->checkOptimizationPackages();

        $this->info('🎉 Asset optimization completed successfully!');
        $this->info('💡 Don\'t forget to:');
        $this->info('   - Set CACHE_STORE=redis in .env');
        $this->info('   - Set SESSION_DRIVER=redis in .env');
        $this->info('   - Configure CDN for static assets');
        $this->info('   - Enable gzip compression on server');
    }

    private function checkOptimizationPackages()
    {
        $packages = [
            'spatie/laravel-image-optimizer' => 'Image optimization',
            'spatie/laravel-responsecache' => 'Response caching',
            'spatie/laravel-query-builder' => 'Query optimization',
        ];

        foreach ($packages as $package => $description) {
            if (!File::exists(base_path("vendor/{$package}"))) {
                $this->warn("⚠️ Consider installing {$package} for {$description}");
            }
        }
    }
}
