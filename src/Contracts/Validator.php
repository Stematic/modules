<?php

declare(strict_types=1);

namespace Stematic\Modules\Contracts;

interface Validator
{
    /**
     * Returns the valid status of the data against the JSON schema.
     */
    public function valid(): bool;

    /**
     * Returns an array of validation errors.
     *
     * @return array<array-key, string>
     */
    public function errors(): array;
}
