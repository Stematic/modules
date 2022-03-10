<?php

declare(strict_types=1);

namespace Stematic\Modules\Contracts;

interface ModuleAuthorInterface
{
    /**
     * Creates a new Module Author based on a passed array of decoded JSON.
     */
    public static function create(array $data): self;

    /**
     * Returns the author name.
     */
    public function name(): string;

    /**
     * Returns the authors' email address.
     */
    public function email(): string;

    /**
     * Returns the authors' website.
     */
    public function website(): string;
}
