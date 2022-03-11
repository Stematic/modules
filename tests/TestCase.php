<?php

declare(strict_types=1);

namespace Stematic\Modules\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Foundation\Application;
use Stematic\Modules\Providers\ServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * @param Application $app
     *
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }
}
