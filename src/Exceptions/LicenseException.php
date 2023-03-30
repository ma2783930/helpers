<?php

namespace Helpers\Exceptions;

use Exception;
use Throwable;

class LicenseException extends  Exception {
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if (empty($message)) {
            $message = __('Invalid License');
        }
        parent::__construct($message, $code, $previous);
    }
}
