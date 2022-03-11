<?php

declare(strict_types=1);

namespace Stematic\Modules\Contracts;

use Illuminate\Support\Collection;
use Stematic\Modules\ComposerPackage;

interface Module
{
    /**
     * Factory method to create a new Module instance.
     */
    public static function make(ComposerPackage $package): self;

    /**
     * Returns the human-readable name for the module.
     */
    public function name(): string;

    /**
     * Returns a short description of what the module does.
     */
    public function description(): ?string;

    /**
     * Returns the module version information (as defined in the schema).
     */
    public function version(): string;

    /**
     * Returns an array of module authors.
     *
     * @return Collection<array-key, Author>
     */
    public function authors(): Collection;

    /**
     * Returns the instance of the composer package.
     */
    public function package(): ComposerPackage;
}
