<?php

namespace Helpers\Classes;

class OS
{

    const OS_UNKNOWN = 1;
    const OS_WIN     = 2;
    const OS_LINUX   = 3;
    const OS_OSX     = 4;

    /**
     * @return int
     */
    public static function getOS(): int
    {
        return match (true) {
            str_starts_with(strtoupper(PHP_OS), 'DAR') => self::OS_OSX,
            str_starts_with(strtoupper(PHP_OS), 'WIN') => self::OS_WIN,
            str_starts_with(strtoupper(PHP_OS), 'LINUX') => self::OS_LINUX,
            default => self::OS_UNKNOWN
        };
    }

    public static function getMac(): ?string
    {
        if (self::getOS() == self::OS_WIN) {
            return explode(' ', exec('getmac'))[0];
        }

        return null;
    }
}
