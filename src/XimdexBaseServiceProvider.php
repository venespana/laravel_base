<?php

namespace Ximdex;


use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class XimdexBaseServiceProvider extends EventServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
        $this->loadViewsFrom(__DIR__ . "/../resources/views/adminlte", 'adminlte');

        $this->publishes([
            __DIR__ . '/../resources/views/adminlte' => resource_path('views/vendor/adminlte'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../public/adminlte' => public_path('/vendor/adminlte'),
        ], 'assets');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
