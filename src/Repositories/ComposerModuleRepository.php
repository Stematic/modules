<?php

declare(strict_types=1);

namespace Stematic\Modules\Repositories;

use Composer\InstalledVersions;
use Illuminate\Support\Collection;
use Stematic\Modules\ComposerPackage;
use Stematic\Modules\Contracts\Module as ModuleContract;
use Stematic\Modules\Module;

class ComposerModuleRepository extends BaseRepository
{
    /**
     * Returns a collection of all loaded modules.
     *
     * @return Collection<array-key, ModuleContract>
     */
    public function all(): Collection
    {
        return $this->modules;
    }

    /**
     * Returns a module by the given slug.
     * E.g. "stematic/my-module".
     */
    public function get(string $slug): ModuleContract
    {
        return $this->modules->get($slug);
    }

    /**
     * Builds the internal module collection with entries from composer.
     */
    protected function build(): void
    {
        $this->modules = collect(InstalledVersions::getInstalledPackages())
            ->map(static function (string $package): ComposerPackage {
                return resolve(ComposerPackage::class, ['name' => $package]);
            })
            ->filter(static function (ComposerPackage $package): bool {
                return $package->valid();
            })
            ->map(static function (ComposerPackage $package): ModuleContract {
                /** @var ModuleContract $module */
                $module = Module::make($package);

                return $module;
            });
    }
}
