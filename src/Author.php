<?php

declare(strict_types=1);

namespace Stematic\Modules;

use Illuminate\Contracts\Support\Arrayable;
use Stematic\Modules\Contracts\Author as AuthorContract;

class Author implements AuthorContract, Arrayable
{
    /**
     * The module author data (json decoded).
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * @param array<string, mixed> $data
     */
    protected function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Creates a new Module Author based on decoded JSON from the module schema.
     *
     * @param array<string, mixed> $data
     */
    public static function make(array $data): self
    {
        return new self($data);
    }

    /**
     * Returns the author name.
     */
    public function name(): string
    {
        return $this->data['name'];
    }

    /**
     * Returns the authors' email address.
     */
    public function email(): ?string
    {
        return $this->data['email'] ?? null;
    }

    /**
     * Returns the authors' website.
     */
    public function website(): ?string
    {
        return $this->data['website'] ?? null;
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
            'email' => $this->email(),
            'website' => $this->website(),
        ];
    }
}
