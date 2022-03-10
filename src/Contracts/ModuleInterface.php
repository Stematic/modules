<?php

declare(strict_types=1);

namespace Stematic\Modules\Contracts;

interface ModuleInterface
{
    /**
     * Creates a new Module based on a passed in JSON string.
     */
    public static function createFromJson(string $json, ?string $path = null): self;

    /**
     * Returns the human-readable name for the module.
     */
    public function name(): string;

    /**
     * Returns the modules' vendor string.
     */
    public function vendor(): string;

    /**
     * Returns the modules' slug string.
     */
    public function slug(): string;

    /**
     * Returns a short description of what the module does.
     */
    public function description(): string;

    /**
     * Returns an array of module authors.
     */
    public function authors(): array;

    /**
     * Returns the module version information (as defined in the schema).
     */
    public function version(): string;

    /**
     * Returns the modules' composer version string.
     */
    public function composerVersion(): string;

    /**
     * Returns whether the structure of the module JSON file is valid.
     */
    public function valid(): bool;

    /**
     * Returns the modules' JSON schema as a string.
     */
    public function schema(): string;

    /**
     * Returns the raw JSON decoded module data.
     */
    public function raw(): array;

    /**
     * Returns an array of errors for an invalid schema.
     */
    public function errors(): array;
}
