<?php

namespace Helpers\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Helpers\Models\ApplicationException;
use Throwable;

class CustomHandler extends ExceptionHandler
{
    /**
     * @param \Throwable $throwable
     * @return void
     * @throws \Throwable
     */
    public function report(Throwable $throwable)
    {
        parent::report($throwable);
    }

    /**
     * @param            $request
     * @param \Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson() && $e instanceof ValidationException) {
            return response()
                ->json([
                    'message' => $e->validator->getMessageBag()->first(),
                    'errors'  => $e->validator->getMessageBag()
                ])
                ->setStatusCode(422);
        }

        if ($e instanceof LicenseException) {
            return response()->view('helpers::errors.license');
        }

        if ($e instanceof LicenseExpirationException) {
            return response()->view('helpers::errors.expired');
        }

        return parent::render($request, $e);
    }
}
