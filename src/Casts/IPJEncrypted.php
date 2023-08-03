<?php

namespace Helpers\Casts;

use Helpers\Facades\IPJEncryption;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class IPJEncrypted implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        return IPJEncryption::decrypt($value);
    }

    public function set($model, string $key, mixed $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        return IPJEncryption::encrypt($value);
    }
}
