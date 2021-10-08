<?php

namespace Combindma\SendinBlueTracker;

use Illuminate\Support\Facades\View;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SendinBlueTrackerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('sendinblue-tracker')
            ->hasConfigFile();
    }

    public function packageBooted()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sendinbluetracker');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/combindma'),
        ], 'views');

        View::creator(
            ['sendinbluetracker::head', 'sendinbluetracker::body'],
            'Combindma\SendinBlueTracker\ScriptViewCreator'
        );
    }

    public function packageRegistered()
    {
        $this->app->singleton('sendinblue-tracker', function () {
            return new SendinBlueTracker();
        });
    }
}
