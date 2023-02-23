<?php

namespace Helpers\Providers;

use Helpers\Models\View\Place;
use Helpers\Rules\ModelExists;
use Illuminate\Support\Facades\Validator;
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
        $this->registerValidationRules();
    }

    /**
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     */
    public function registerValidationRules(): void
    {
        Rule::macro('modelExists', function ($table, $column = 'id', $withoutExpired = true) {
            return new ModelExists($table, $column, $withoutExpired);
        });

        Validator::extend('place_exists', function ($attribute, $value, $parameters, $validator) {
            $place = Place::for($value)->first();
            if (empty($place)) return false;

            return !empty($place->country_id) ||
                !empty($place->province_id) ||
                !empty($place->city_id);
        });

        Validator::extend('jalali_year', function ($attribute, $value, $parameters, $validator) {
            $year = (int)$value;
            return $year > 1357 && $year < (int)verta()->addYears(10)->format('Y');
        });

        Validator::extend('first_password', function ($attribute, $value, $parameters, $validator) {
            $user = auth()->user();
            return $user->checkUserPassword($value);
        });

        Validator::extend('second_password', function ($attribute, $value, $parameters, $validator) {
            $user = auth()->user();
            return $user->checkSecondUserPassword($value);
        });
    }
}
