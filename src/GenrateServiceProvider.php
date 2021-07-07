<?php

namespace Lettrue\Genrate;

use Illuminate\Console\Application as Artisan;
use Lettrue\Genrate\Commands\AddController;
use Lettrue\Genrate\Commands\AddModel;
use Lettrue\Genrate\Commands\AddRequest;
use Illuminate\Support\ServiceProvider;
use Lettrue\Genrate\Commands\AddService;

class GenrateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    protected $commands = [
        'lettrue.controller' => AddController::class,
        'lettrue.model' => AddModel::class,
        'lettrue.request' => AddRequest::class,
        'lettrue.service' => AddService::class,
    ];

    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }


    /**
     * Register the package's custom Artisan commands.
     *
     * @param  array|mixed  $commands
     * @return void
     */
    public function commands($commands)
    {
        $commands = is_array($commands) ? $commands : func_get_args();

        Artisan::starting(function ($artisan) use ($commands) {
            $artisan->resolveCommands($commands);
        });
    }
}
