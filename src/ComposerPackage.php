<?php

declare(strict_types=1);

namespace Stematic\Modules;

use Composer\InstalledVersions;
use Illuminate\Support\Arr;
use Stematic\Modules\Concerns\ReadsComposerManifest;
use Stematic\Modules\Contracts\Discoverable;

use function realpath;

class ComposerPackage implements Discoverable
{
    use ReadsComposerManifest;

    /**
     * The name of the composer.json file.
     */
    protected const COMPOSER_JSON = 'composer.json';

    /**
     * The decoded composer.json manifest.
     *
     * @var array<string, mixed>
     */
    protected array $manifest;

    /**
     * Whether the package is an actual module.
     */
    protected bool $valid;

    public function __construct(protected string $name) {
        $this->build();
    }

    /**
     * Returns the installed name of the module (the composer package).
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * The installation directory of the module.
     */
    public function path(): string
    {
        return InstalledVersions::getInstallPath($this->name) ?? '';
    }

    /**
     * Returns a list of service providers to automatically load when the module is booted.
     *
     * @return array<array-key, string>
     */
    public function providers(): array
    {
        return Arr::get($this->manifest, 'extra.modules.providers', []);
    }

    /**
     * Returns a list of facades to automatically register when the module is booted.
     *
     * @return array<array-key, string>
     */
    public function aliases(): array
    {
        return Arr::get($this->manifest, 'extra.modules.aliases', []);
    }

    /**
     * Returns the version name as installed from Composer.
     */
    public function version(): string
    {
        return InstalledVersions::getVersion($this->name);
    }

    /**
     * Returns the JSON decoded composer.json manifest data.
     *
     * @return array<string, mixed>
     */
    public function manifest(): array
    {
        return $this->manifest;
    }

    /**
     * Returns whether this module is an actual package we care about (is a module).
     */
    public function valid(): bool
    {
        return $this->valid;
    }

    /**
     * Validates and retrieves the package manifest file.
     */
    protected function build(): void
    {
        $this->valid = false;
        $this->manifest = [];

        $path = InstalledVersions::getInstallPath($this->name);

        // InstalledVersion will send through the current package as well.
        if ($path === null || $this->name === 'stematic/modules') {
            return;
        }

        $this->manifest = $this->parseComposerManifest(realpath($path));

        $this->valid = ($this->manifest['type'] ?? '') === config('modules.type');
    }
}
