<?php

declare(strict_types=1);

namespace Stematic\Modules;

use Illuminate\Support\Collection;
use JsonException;
use Stematic\Modules\Contracts\ModuleInterface;
use Stematic\Modules\Concerns\DiscoverableModuleTrait;
use Stematic\Modules\Concerns\SerializesModuleSchemaTrait;
use Stematic\Modules\Concerns\ValidatesModuleSchemaTrait;
use Stematic\Modules\Contracts\DiscoverableModuleInterface;
use JsonSchema\Validator;
use Illuminate\Contracts\Support\Arrayable;

use function json_decode;
use function is_string;
use function file_get_contents;
use function realpath;

use const JSON_THROW_ON_ERROR;

class Module implements ModuleInterface, DiscoverableModuleInterface, Arrayable
{
    use DiscoverableModuleTrait;
    use ValidatesModuleSchemaTrait;
    use SerializesModuleSchemaTrait;

    /**
     * The path for the JSON schema to validate against.
     */
    private const SCHEMA_PATH = __DIR__ . '/../module.json';

    /**
     * The module data (json decoded).
     *
     * @var array
     */
    protected array $data = [];

    /**
     * The module author data (json decoded).
     *
     * @var array
     */
    protected array $authors = [];

    /**
     * The JSON Schema Validator instance.
     *
     * @var Validator
     */
    protected Validator $validator;

    protected function __construct(array $data)
    {
        $this->data = $data;
        $this->validator = $this->validate();

        foreach ($data['authors'] ?? [] as $author) {
            $this->authors[] = ModuleAuthor::create($author);
        }
    }

    /**
     * Creates a new Module based on a passed in JSON string.
     *
     * @throws JsonException
     */
    public static function createFromJson(string $json, ?string $path = null): ModuleInterface
    {
        $data = json_decode($json, true, 8, JSON_THROW_ON_ERROR);
        $data['path'] = $path;

        $module = new self($data);

        // Parse the composer.json file to retrieve the service providers to
        // automatically load for the module and the installed version, etc.
        $module->parseComposerManifest();

        return $module;
    }

    /**
     * Returns the directory path to the installed module on disk.
     */
    public function path(): string
    {
        return is_string($this->data['path'] ?? '')
            ? $this->data['path']
            : '';
    }

    /**
     * Returns the human-readable name for the module.
     */
    public function name(): string
    {
        return is_string($this->data['name'] ?? '')
            ? $this->data['name']
            : '';
    }

    /**
     * Returns the modules' vendor string.
     */
    public function vendor(): string
    {
        return is_string($this->data['vendor'] ?? '')
            ? $this->data['vendor']
            : '';
    }

    /**
     * Returns the modules' slug string.
     */
    public function slug(): string
    {
        return is_string($this->data['slug'] ?? '')
            ? $this->data['slug']
            : '';
    }

    /**
     * Returns a short description of what the module does.
     */
    public function description(): string
    {
        return is_string($this->data['description'] ?? '')
            ? $this->data['description']
            : '';
    }

    /**
     * Returns an array of module authors.
     */
    public function authors(): array
    {
        return $this->authors;
    }

    /**
     * Returns the module version information (as defined in the schema).
     */
    public function version(): string
    {
        return is_string($this->data['version'] ?? '')
            ? $this->data['version']
            : '';
    }

    /**
     * Returns the modules' composer version string.
     */
    public function composerVersion(): string
    {
        return is_string($this->data['composer_version'] ?? '')
            ? $this->data['composer_version']
            : '';
    }

    /**
     * Returns whether the structure of the module JSON file is valid.
     */
    public function valid(): bool
    {
        return $this->validator->isValid();
    }

    /**
     * Returns an array of errors for an invalid schema.
     */
    public function errors(): array
    {
        if ($this->valid()) {
            return [];
        }

        return $this->validator->getErrors();
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name(),
            'vendor' => $this->vendor(),
            'slug' => $this->slug(),
            'description' => $this->description(),
            'authors' => (new Collection($this->authors()))->toArray(),
            'version' => $this->version(),
            'composer_version' => $this->composerVersion(),
            'path' => $this->path(),
            'providers' => $this->providers(),
            'aliases' => $this->aliases(),
        ];
    }

    /**
     * Returns the raw JSON decoded module data.
     */
    public function raw(): array
    {
        return $this->data;
    }

    /**
     * Returns the modules' JSON schema as a string.
     */
    public function schema(): string
    {
        return file_get_contents((string) realpath(self::SCHEMA_PATH));
    }
}
