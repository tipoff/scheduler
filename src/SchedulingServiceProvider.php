<?php

namespace Tipoff\Scheduling;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tipoff\Scheduling\Commands\SchedulingCommand;

class SchedulingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('scheduling')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_scheduling_table')
            ->hasCommand(SchedulingCommand::class);
    }
}
