<?php

declare(strict_types=1);

namespace Stematic\Modules;

use Illuminate\Contracts\Support\Arrayable;
use Stematic\Modules\Contracts\ModuleAuthorInterface;

use function is_string;

class ModuleAuthor implements ModuleAuthorInterface, Arrayable
{
    /**
     * The module author data (json decoded).
     *
     * @var array
     */
    protected array $data = [];

    protected function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Creates a new Module Author based on a passed in JSON string.
     */
    public static function create(array $data): ModuleAuthorInterface
    {
        return new self($data);
    }

    /**
     * Returns the author name.
     */
    public function name(): string
    {
        return is_string($this->data['name'] ?? '')
            ? $this->data['name']
            : '';
    }

    /**
     * Returns the authors' email address.
     */
    public function email(): string
    {
        return is_string($this->data['email'] ?? '')
            ? $this->data['email']
            : '';
    }

    /**
     * Returns the authors' website.
     */
    public function website(): string
    {
        return is_string($this->data['website'] ?? '')
            ? $this->data['website']
            : '';
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name(),
            'email' => $this->email(),
            'website' => $this->website(),
        ];
    }
}
