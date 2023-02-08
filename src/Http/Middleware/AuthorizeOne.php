<?php

namespace Helpers\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuthorizeOne
{
    /**
     * The gate instance.
     *
     * @var \Illuminate\Contracts\Auth\Access\Gate
     */
    protected Gate $gate;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param mixed                    ...$abilities
     * @return mixed
     *
     */
    public function handle(Request $request, Closure $next, ...$abilities): mixed
    {
        $accessDenied = true;
        foreach ($abilities as $ability) {
            if ($this->gate->allows($ability)) {
                $accessDenied = false;
                break;
            }
        }

        abort_if($accessDenied, 403);

        return $next($request);
    }

    /**
     * Get the arguments parameter for the gate.
     *
     * @param \Illuminate\Http\Request $request
     * @param array|null               $models
     * @return \Illuminate\Database\Eloquent\Model|array|string
     */
    protected function getGateArguments(Request $request, ?array $models): Model|array|string
    {
        if (is_null($models)) {
            return [];
        }

        return collect($models)->map(function ($model) use ($request) {
            return $model instanceof Model ? $model : $this->getModel($request, $model);
        })->all();
    }

    /**
     * Get the model to authorize.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $model
     * @return \Illuminate\Database\Eloquent\Model|string|null
     */
    protected function getModel(Request $request, string $model): Model|string|null
    {
        if ($this->isClassName($model)) {
            return trim($model);
        } else {
            return $request->route($model, null) ??
                ((preg_match("/^['\"](.*)['\"]$/", trim($model), $matches)) ? $matches[1] : null);
        }
    }

    /**
     * Checks if the given string looks like a fully qualified class name.
     *
     * @param string $value
     * @return bool
     */
    protected function isClassName(string $value): bool
    {
        return str_contains($value, '\\');
    }
}
