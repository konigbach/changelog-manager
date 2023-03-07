<?php

namespace Konigbach\ChangelogManager;

use Illuminate\Support\ServiceProvider;
use Konigbach\ChangelogManager\Commands\AddChangelogCommand;
use Konigbach\ChangelogManager\Commands\ChangelogGenerateCommand;

class ChangelogManagerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'changelog-manager');

        if ($this->isNotLumen()) {
            $this->publishes([
                __DIR__ . '/../config/changelog-manager.php' => config_path('changelog-manager.php'),
            ], 'changelog-generator');

            $this->publishes([
                __DIR__ . '/../resources/views/changelog-manager' => resource_path('views/vendor/changelog-manager'),
            ]);
        }
    }

    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddChangelogCommand::class,
                ChangelogGenerateCommand::class,
            ]);
        }
    }

    protected function isNotLumen(): bool
    {
        return stripos(app()->version(), 'lumen') === false;
    }
}
