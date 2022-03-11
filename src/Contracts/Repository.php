<?php

declare(strict_types=1);

namespace Stematic\Modules\Contracts;

use Illuminate\Support\Collection;

interface Repository
{
    /**
     * Returns a collection of all loaded modules.
     *
     * @return Collection<array-key, Module>
     */
    public function all(): Collection;

    /**
     * Returns a module by the given slug.
     * E.g. "stematic/my-module".
     */
    public function get(string $slug): Module;
}
