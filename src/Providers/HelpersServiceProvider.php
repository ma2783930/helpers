<?php

namespace Helpers\Providers;

use Helpers\Classes\IPJEncryption;
use Helpers\Http\Middleware\LicenseChecker;
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
        $key = config('encryption.enc_pw');

        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $this->app->bind('ipj-encryption', fn() => new IPJEncryption($key));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerValidationRules();
        $this->mergeConfigFrom(__DIR__ . '/../../config/encryption.php', 'encryption');
        $this->mergeConfigFrom(__DIR__ . '/../../config/license.php', 'license');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'helpers');
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/helpers'),
            __DIR__ . '/../../config/encryption.php' => config_path('encryption.php'),
        ]);
        //$this->app['router']->pushMiddlewareToGroup('web', LicenseChecker::class);
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
