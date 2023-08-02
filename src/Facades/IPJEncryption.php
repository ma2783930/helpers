<?php

namespace Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string encrypt($input)
 * @method static string decrypt($input)
 */
class IPJEncryption extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ipj-encryption';
    }
}
