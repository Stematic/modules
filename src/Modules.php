<?php

declare(strict_types=1);

namespace Stematic\Modules;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Traits\ForwardsCalls;

use Stematic\Modules\Repositories\ComposerModuleRepository;

use function unserialize;
use function serialize;

/**
 * @mixin Collection<string, Module>
 */
class Modules
{
    use ForwardsCalls;

    /**
     * A collection of installed modules.
     *
     * @var Collection
     */
    protected Collection $modules;

    public function __construct(ComposerModuleRepository $repository)
    {
        $this->modules = $repository->all();
    }

    /**
     * Forwards calls to the collection instance.
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->forwardCallTo($this->modules, $name, $arguments);
    }
}
