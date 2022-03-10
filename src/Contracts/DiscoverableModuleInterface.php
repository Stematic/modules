<?php

declare(strict_types=1);

namespace Stematic\Modules\Contracts;

interface DiscoverableModuleInterface
{
    /**
     * The installation directory of the module.
     */
    public function path(): string;

    /**
     * Returns the installed name of the module (the composer package).
     */
    public function package(): string;

    /**
     * Returns a list of service providers to automatically load when the module is booted.
     */
    public function providers(): array;

    /**
     * Returns a list of facades to automatically register when the module is booted.
     */
    public function aliases(): array;
}
