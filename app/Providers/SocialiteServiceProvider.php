<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Apple\AppleExtendSocialite;

class SocialiteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(
            SocialiteWasCalled::class,
            [AppleExtendSocialite::class, 'handle']
        );
    }
}
