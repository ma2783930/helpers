<?php

namespace Helpers\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;
use Closure;

class CleanInputs extends TransformsRequest
{

    /**
     * All of the registered skip callbacks.
     *
     * @var array
     */
    protected static array $skipCallbacks = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach (static::$skipCallbacks as $callback) {
            if ($callback($request)) {
                return $next($request);
            }
        }

        return parent::handle($request, $next);
    }

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return string|null
     */
    protected function transform($key, $value): ?string
    {
        if (empty($value)) return $value;
        if (is_numeric($value)) return $value;

        return htmlspecialchars($value);
    }

    /**
     * Register a callback that instructs the middleware to be skipped.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function skipWhen(Closure $callback): void
    {
        static::$skipCallbacks[] = $callback;
    }
}
