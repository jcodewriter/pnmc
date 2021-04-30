<?php

namespace App\Providers;

use App\Asset;
use App\Observers\AssetObserver;
use App\AssetHistory;
use App\Observers\AssetHistoryObserver;
use App\Exchange;
use App\Observers\ExchangeObserver;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cookie;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // OBSERVERS
        Asset::observe(AssetObserver::class);
        AssetHistory::observe(AssetHistoryObserver::class);
        Exchange::observe(ExchangeObserver::class);


        // BLADE DIRECTIVES
        Blade::if('env', function ($environment) {
            return app()->environment($environment);
        });

        Blade::directive('datetime', function ($expression) {
            return "<?php
                echo \App\Helpers\Formatter::dateTime($expression);
            ?>";
        });
        Blade::directive('date', function ($expression) {
            return "<?php
                echo \App\Helpers\Formatter::date($expression);
            ?>";
        });
        Blade::directive('time', function ($expression) {
            return "<?php
                echo \App\Helpers\Formatter::time($expression);
            ?>";
        });
        Blade::directive('money_format', function ($expression) {
            return "<?php
                echo \App\Helpers\Formatter::money($expression);
            ?>";
        });
        Blade::directive('number_format', function ($expression) {
            return "<?php
                echo \App\Helpers\Formatter::number($expression);
            ?>";
        });
        Blade::directive('number_format_full', function ($expression) {
            return "<?php
                echo \App\Helpers\Formatter::numberFull($expression);
            ?>";
        });
        Blade::directive('change_format', function ($expression) {
            return "<?php
                echo \App\Helpers\Formatter::change($expression);
            ?>";
        });
        Blade::directive('theme', function () {
            return "<?php
                if (!Cookie::has('pegnet_theme'))
                {
                    echo '';
                }
                else
                {
                    \$theme = Cookie::get('pegnet_theme');
                    echo (in_array(\$theme, ['light', 'dark']) ? ('theme-' . \$theme)  : '');
                }
            ?>";
        });
    }
}
