<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Enabling Eloquent "Strict Mode".
         *
         * preventLazyLoading + preventSilentlyDiscardingAttributes +
         * preventAccessingMissingAttributes
         */
        Model::shouldBeStrict(! App::environment('production'));

        /**
         * Logging queries on local environment.
         */
        if (App::environment('local')) {
            DB::listen(fn (QueryExecuted $query) => logger(sprintf(
                '%s %s',
                $query->time,
                Str::replaceArray('?', $query->bindings, $query->sql)
            )));
        }
    }
}
