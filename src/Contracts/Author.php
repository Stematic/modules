<?php

declare(strict_types=1);

namespace Stematic\Modules\Contracts;

interface Author
{
    /**
     * Returns the author name.
     */
    public function name(): string;

    /**
     * Returns the authors' email address.
     */
    public function email(): ?string;

    /**
     * Returns the authors' website.
     */
    public function website(): ?string;
}
