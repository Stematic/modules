<?php

declare(strict_types=1);

namespace Stematic\Modules\Concerns;

use Illuminate\Support\Arr;
use JsonException;
use Stematic\Modules\Module;

use function is_string;
use function is_array;
use function file_exists;
use function file_get_contents;
use function realpath;
use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * @mixin Module
 */
trait DiscoverableModuleTrait
{
    /**
     * Returns the installed name of the module (the composer package).
     */
    public function package(): string
    {
        return is_string($this->data['package'] ?? '')
            ? $this->data['package']
            : '';
    }

    /**
     * Returns a list of service providers to automatically register when the module is booted.
     */
    public function providers(): array
    {
        return is_array($this->data['providers'] ?? [])
            ? $this->data['providers']
            : [];
    }

    /**
     * Returns a list of facades to automatically register when the module is booted.
     */
    public function aliases(): array
    {
        return is_array($this->data['aliases'] ?? [])
            ? $this->data['aliases']
            : [];
    }

    /**
     * Reads the modules' composer.json file to determine which service providers
     * should be autoloaded when modules are initialised.
     */
    protected function parseComposerManifest(): void
    {
        try {
            $manifest = $this->getComposerManifest();
        } catch (JsonException $e) {
            // Invalid JSON in the modules' composer file.
            // Nothing we really need to care about.

            return;
        }

        $this->data['package'] = $manifest['name'] ?? '';
        $this->data['composer_version'] = $manifest['version'] ?? '';
        $this->data['providers'] = Arr::get($manifest, 'extra.modules.providers', []);
        $this->data['aliases'] = Arr::get($manifest, 'extra.modules.aliases', []);
    }

    /**
     * Returns the contents of the composer.json file for the Module.
     *
     * @throws JsonException
     */
    protected function getComposerManifest(): array
    {
        if (! file_exists($this->path() . '/composer.json')) {
            // No need to do anything here, technically this should never happen anyway
            // as it wouldn't be in the composer installed list; maybe a broken package?

            return [];
        }

        $json = file_get_contents(realpath($this->path() . '/composer.json'));

        return json_decode($json, true, 8, JSON_THROW_ON_ERROR);
    }
}
