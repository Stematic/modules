<?php

declare(strict_types=1);

namespace Stematic\Modules;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\ForwardsCalls;
use Stematic\Modules\Repositories\ComposerModuleRepository;

/**
 * @mixin Collection<string, Module>
 */
class Modules
{
    use ForwardsCalls;

    /**
     * A collection of installed modules.
     *
     * @var Collection<array-key, \Stematic\Modules\Contracts\Module>
     */
    protected Collection $modules;

    public function __construct(ComposerModuleRepository $repository)
    {
        $this->modules = $repository->all();
    }

    /**
     * Forwards calls to the collection instance.
     *
     * @param array<array-key, mixed> $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->forwardCallTo($this->modules, $name, $arguments);
    }
}
