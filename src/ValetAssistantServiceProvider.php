<?php

namespace Gbuckingham89\ValetAssistant;

use Gbuckingham89\ValetAssistant\Commander\Commander;
use Gbuckingham89\ValetAssistant\Commander\ExecCommander;
use Gbuckingham89\ValetAssistant\Entities\Repositories\Projects\Repository;
use Illuminate\Support\ServiceProvider;

class ValetAssistantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('valet-assistant.php'),
            ], 'valet-assistant-config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'valet-assistant');

        $this->app->singleton(Commander::class, function () {
            return new ExecCommander(strval(config('valet-assistant.env_path')));
        });

        $this->app->bind(Repository::class, strval(config('valet-assistant.projects_repository_class')));

        $this->app->singleton(ValetAssistant::class, function () {

            /** @var \Gbuckingham89\ValetAssistant\Commander\Commander $commander */
            $commander = $this->app->make(Commander::class);

            /** @var \Gbuckingham89\ValetAssistant\Entities\Repositories\Projects\Repository $projectsRepo */
            $projectsRepo = $this->app->make(Repository::class);

            return new ValetAssistant($commander, $projectsRepo);
        });
    }
}
