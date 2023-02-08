<?php

namespace Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\UploadedFile
 */
class BinaryFile extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'binary';
    }
}
