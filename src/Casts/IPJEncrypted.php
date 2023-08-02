<?php

namespace Helpers\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use Helpers\Facades\IPJEncryption;
use Illuminate\Database\Eloquent\Model;

class IPJEncrypted implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        return IPJEncryption::decrypt($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        return IPJEncryption::encrypt($value);
    }
}
