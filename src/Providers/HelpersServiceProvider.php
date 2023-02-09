<?php

namespace Helpers\Providers;

use Helpers\Rules\ModelExists;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;

class HelpersServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        Rule::macro('modelExists', function ($table, $column = 'id', $withoutExpired = true) {
            return new ModelExists($table, $column, $withoutExpired);
        });
    }
}
