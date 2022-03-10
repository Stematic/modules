<?php

declare(strict_types=1);

namespace Stematic\Modules\Concerns;

use Stematic\Modules\Module;

/**
 * @mixin Module
 */
trait SerializesModuleSchemaTrait
{
    /**
     * Serializes the module data into an array.
     */
    public function __serialize(): array
    {
        return $this->raw();
    }

    /**
     * Called when the module is unserialized from an array.
     */
    public function __unserialize(array $data): void
    {
        $this->data = $data;
        $this->validator = $this->validate();
    }
}
