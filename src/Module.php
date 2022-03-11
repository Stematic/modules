<?php

declare(strict_types=1);

namespace Stematic\Modules;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonException;
use Stematic\Modules\Contracts\Module as ModuleContract;
use Stematic\Modules\Contracts\Author as AuthorContract;

use function json_decode;
use function file_get_contents;
use function realpath;

use const JSON_THROW_ON_ERROR;

class Module implements ModuleContract, Arrayable
{
    /**
     * The path for the JSON schema to validate against.
     */
    private const SCHEMA_PATH = __DIR__ . '/../module.json';

    /**
     * @param array<array-key, string> $data
     */
    protected function __construct(protected array $data, protected ComposerPackage $package)
    {
    }

    /**
     * @throws JsonException
     */
    public static function make(ComposerPackage $package): Module
    {
        $path = Str::finish(realpath($package->path()), '/') . 'module.json';
        $manifest = json_decode(file_get_contents($path), true, 8, JSON_THROW_ON_ERROR);

        return new self($manifest, $package);
    }

    /**
     * Returns the human-readable name for the module.
     */
    public function name(): string
    {
        return $this->data['name'] ?? $this->package->name();
    }

    /**
     * Returns a short description of what the module does.
     */
    public function description(): ?string
    {
        return $this->data['description'] ?? '';
    }

    /**
     * Returns the module version information (as defined in the schema).
     */
    public function version(): string
    {
        return $this->data['version'] ?? $this->package->version();
    }

    /**
     * Returns an array of module authors.
     *
     * @return Collection<array-key, AuthorContract>
     */
    public function authors(): Collection
    {
        /* @phpstan-ignore-next-line */
        return collect($this->data['authors'] ?? [])
            /* @phpstan-ignore-next-line */
            ->map(static function (array $author): Author {
                return Author::make($author);
            });
    }

    /**
     * Returns the instance of the composer package.
     */
    public function package(): ComposerPackage
    {
        return $this->package;
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name(),
            'slug' => $this->package->name(),
            'description' => $this->description(),
            'authors' => $this->authors()->toArray(),
            'version' => $this->version(),
            'composer_version' => $this->package->version(),
            'providers' => $this->package->providers(),
            'aliases' => $this->package->aliases(),
        ];
    }

    /**
     * Returns the modules' JSON schema as a string.
     */
    public function schema(): string
    {
        return file_get_contents((string) realpath(self::SCHEMA_PATH));
    }
}
