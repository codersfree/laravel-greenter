<?php

namespace CodersFree\LaravelGreenter;

use CodersFree\LaravelGreenter\Senders\ApiBuilder;
use CodersFree\LaravelGreenter\Services\ApiSender;
use CodersFree\LaravelGreenter\Services\ReportService;
use CodersFree\LaravelGreenter\Services\SenderService;
use Illuminate\Support\ServiceProvider;

class GreenterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/greenter.php', 'greenter');

        $this->app->singleton('greenter', function ($app) {
            return new SenderService();
        });

        $this->app->singleton('greenter.report', function ($app) {
            return new ReportService();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../stubs/images/logo.png' => public_path('images/logo.png'),
        ], 'greenter-logo');

        $this->publishes([
            __DIR__.'/../stubs/templates' => config('greenter.report.templates'),
        ], 'greenter-templates');

        $this->publishes([
            __DIR__.'/../stubs/certificate.pem' => public_path('certs/certificate.pem'),
        ], 'greenter-certificate');

        $this->publishes([
            __DIR__.'/../config/greenter.php' => config_path('greenter.php'),
        ], 'greenter-config');

        $this->publishes([
            __DIR__.'/../stubs/images/logo.png' => public_path('images/logo.png'),
            __DIR__.'/../stubs/certificate.pem' => public_path('certs/certificate.pem'),
            __DIR__.'/../config/greenter.php' => config_path('greenter.php'),
        ], 'greenter-laravel');

    }
}