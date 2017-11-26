<?php

namespace Botble\Base\Providers;

use Botble\Base\Commands\CreateFreshDatabaseCommand;
use Botble\Base\Commands\DumpAutoload;
use Botble\Base\Commands\PluginListCommand;
use Botble\Base\Commands\SendUserBirthdayEmailCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Botble\Base\Commands\ClearLogCommand;
use Botble\Base\Commands\InstallCommand;
use Botble\Base\Commands\PluginActivateCommand;
use Botble\Base\Commands\PluginCreateCommand;
use Botble\Base\Commands\PluginDeactivateCommand;
use Botble\Base\Commands\PluginRemoveCommand;
use Botble\Base\Commands\RebuildPermissionsCommand;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                SendUserBirthdayEmailCommand::class,
                CreateFreshDatabaseCommand::class,
            ]);

            $this->app->booted(function () {
                $this->app->make(Schedule::class)->command(SendUserBirthdayEmailCommand::class)->daily();
            });
        }

        $this->commands([
            RebuildPermissionsCommand::class,
            DumpAutoload::class,
            PluginCreateCommand::class,
            PluginActivateCommand::class,
            PluginDeactivateCommand::class,
            PluginRemoveCommand::class,
            ClearLogCommand::class,
            PluginListCommand::class,
        ]);
    }
}
