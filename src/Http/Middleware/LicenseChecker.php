<?php

namespace Helpers\Http\Middleware;

use Closure;
use Hekmatinasser\Verta\Verta;
use Helpers\Classes\OS;
use Helpers\Exceptions\LicenseException;
use Helpers\Exceptions\LicenseExpirationException;
use Illuminate\Http\Request;

class LicenseChecker
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request                                                                          $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Helpers\Exceptions\LicenseException
     * @throws \Helpers\Exceptions\LicenseExpirationException
     */
    public function handle(Request $request, Closure $next)
    {
        $enable = config('license.enable');

        if ($enable) {

            $mac = OS::getMac();
            if ($mac != config('license.mac')) {
                throw new LicenseException();
            }

            $expiresAt = config('license.expires_at');
            if (!empty($expiresAt) && Verta::parse($expiresAt)->isPast()) {
                throw new LicenseExpirationException();
            }

        }

        return $next($request);
    }
}
