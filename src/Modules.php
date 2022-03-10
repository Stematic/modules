<?php

declare(strict_types=1);

namespace Stematic\Modules;

use Composer\InstalledVersions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Traits\ForwardsCalls;
use Stematic\Modules\Contracts\ModuleInterface;
use Stematic\Modules\Contracts\ModuleServiceInterface;

use function unserialize;
use function serialize;
use function realpath;
use function file_exists;
use function file_get_contents;

/**
 * @mixin Collection<string, Module>
 */
class Modules implements ModuleServiceInterface
{
    use ForwardsCalls;

    /**
     * A collection of installed modules.
     *
     * @var Collection
     */
    protected Collection $modules;

    public function __construct()
    {
        // $modules = Cache::get('modules');
        $modules = null;

        if ($modules === null) {
            $modules = $this->build();
        }

        $data = unserialize($modules, ['allowed_classes' => [Module::class, Collection::class]]);

        $this->modules = new Collection($data);
    }

    /**
     * Returns all modules.
     */
    public function all(): Collection
    {
        return $this->modules;
    }

    /**
     * Builds the module cache with a fresh listing of packages.
     */
    public function build(): string
    {
        $this->updatePackagesFromComposer();

        $records = serialize($this->modules);

        Cache::put('modules', $records);

        return $records;
    }

    /**
     * Clears the module cache.
     */
    public function clear(): void
    {
        Cache::forget('modules');
        $this->modules = new Collection();
    }

    /**
     * Retrieves a list of installed packages from Composer.
     */
    protected function updatePackagesFromComposer(): void
    {
        $this->modules = collect(InstalledVersions::getInstalledPackages())
                 ->map(static function (string $item): string {
                     return realpath(InstalledVersions::getInstallPath($item) ?? '') ?: '';
                 })
                 ->filter(static function (string $item): bool {
                     return file_exists($item . '/schema.json');
                 })
                 ->map(static function (string $path): ModuleInterface {
                     return Module::createFromJson(file_get_contents($path . '/schema.json') ?: '', $path);
                 })
                 // ->sortBy(static fn (Module $module): int => $module->priority())
                 ->keyBy(static fn (ModuleInterface $module): string => $module->slug());
    }

    /**
     * Forwards calls to the collection instance.
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->forwardCallTo($this->modules, $name, $arguments);
    }
}
