<?php

declare(strict_types=1);

namespace Stematic\Modules\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/modules.php', 'modules');
    }

    public function register(): void
    {
        // ...
    }
}
