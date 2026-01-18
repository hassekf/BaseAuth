<?php

namespace CharlesHasse\Dau;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use CharlesHasse\Dau\Livewire\Login;
use CharlesHasse\Dau\Livewire\Register;

class DauServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/dau.php',
            'dau'
        );
    }

    public function boot()
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'dau'
        );

        $this->loadRoutesFrom(
            __DIR__ . '/../routes/web.php'
        );

        Livewire::component('dau.login', Login::class);
        Livewire::component('dau.register', Register::class);


        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/dau.php' => config_path('dau.php'),
            ], 'dau-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/dau'),
            ], 'dau-views');
        }
    }
}
