<?php

declare(strict_types=1);

namespace Stematic\Modules\Repositories;

use Illuminate\Support\Collection;
use Stematic\Modules\Contracts\Module as ModuleContract;
use Stematic\Modules\Contracts\Repository;

abstract class BaseRepository implements Repository
{
    /**
     * A collection of built modules.
     *
     * @var Collection<array-key, ModuleContract>
     */
    protected Collection $modules;

    public function __construct()
    {
        $this->build();
    }

    /**
     * Builds the internal collection of modules.
     */
    abstract protected function build(): void;
}
