<?php

namespace Esign\Plytix;

use Illuminate\Support\ServiceProvider;

class PlytixServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([$this->configPath() => config_path('plytix.php')], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'plytix');
    }

    protected function configPath(): string
    {
        return __DIR__ . '/../config/plytix.php';
    }
}
