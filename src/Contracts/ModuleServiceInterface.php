<?php

declare(strict_types=1);

namespace Stematic\Modules\Contracts;

use Illuminate\Support\Collection;

interface ModuleServiceInterface
{
    /**
     * Returns all modules.
     */
    public function all(): Collection;

    /**
     * Builds the list of loaded modules through composer.
     */
    public function build(): string;

    /**
     * Clears the list of modules (including cache) to be rebuilt at a later date.
     */
    public function clear(): void;
}
