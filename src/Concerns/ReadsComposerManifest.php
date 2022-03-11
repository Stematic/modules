<?php

declare(strict_types=1);

namespace Stematic\Modules\Concerns;

use JsonException;
use Stematic\Modules\ComposerPackage;

/**
 * @mixin ComposerPackage
 */
trait ReadsComposerManifest
{
    /**
     * Returns the decoded composer.json file for the package.
     */
    protected function parseComposerManifest(string $path): array
    {
        $file = \Str::finish($path, '/') . self::COMPOSER_JSON;

        if (! file_exists($file)) {
            return [];
        }

        try {
            return json_decode(file_get_contents($file), true, 8, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }
    }
}
