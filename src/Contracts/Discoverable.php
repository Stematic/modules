<?php

declare(strict_types=1);

namespace Stematic\Modules\Contracts;

interface Discoverable
{
    /**
     * Returns the installed name of the module (the composer package).
     */
    public function name(): string;

    /**
     * The installation directory of the module.
     */
    public function path(): string;

    /**
     * Returns a list of service providers to automatically load when the module is booted.
     *
     * @return array<array-key, string>
     */
    public function providers(): array;

    /**
     * Returns a list of facades to automatically register when the module is booted.
     *
     * @return array<array-key, string>
     */
    public function aliases(): array;

    /**
     * Returns the version name as installed from Composer.
     */
    public function version(): string;
}
