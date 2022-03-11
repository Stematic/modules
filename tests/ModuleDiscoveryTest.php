<?php

declare(strict_types=1);

namespace Stematic\Modules\Tests;

use Stematic\Modules\Modules;

class ModuleDiscoveryTest extends TestCase
{
    public function testThatModulesCanBeDiscovered(): void
    {
        $service = resolve(Modules::class);

        dd($service->toArray());
        $this->assertTrue(true);
    }
}
