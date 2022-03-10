<?php

declare(strict_types=1);

namespace Stematic\Modules\Concerns;

use JsonSchema\Validator;
use Stematic\Modules\Contracts\ModuleInterface;

/**
 * @mixin ModuleInterface
 */
trait ValidatesModuleSchemaTrait
{
    /**
     * Validates the module schema with a JSON validator, returning the validated instance.
     */
    protected function validate(): Validator
    {
        $validator = resolve(Validator::class);

        // The validator modifies the array data
        // We don't need this to happen, so clone the array first.
        $data = $this->raw();

        $validator->validate($data, $this->schema());

        return $validator;
    }
}
