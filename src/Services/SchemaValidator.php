<?php

declare(strict_types=1);

namespace Stematic\Modules\Services;

use JsonSchema\Validator as JsonSchemaValidator;
use Stematic\Modules\Contracts\Validator;

class SchemaValidator implements Validator
{
    public function __construct(
        protected JsonSchemaValidator $validator,
        protected string $schema,
        protected array $data
    ) {
        $validator->validate($data, $schema);
    }

    /**
     * Returns the valid status of the data against the JSON schema.
     */
    public function valid(): bool
    {
        return $this->validator->isValid();
    }

    /**
     * Returns an array of validation errors.
     *
     * @return array<array-key, string>
     */
    public function errors(): array
    {
        if ($this->valid()) {
            return [];
        }

        return $this->validator->getErrors();
    }
}
