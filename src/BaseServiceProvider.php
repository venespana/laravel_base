<?php

namespace VD;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class BaseServiceProvider extends EventServiceProvider
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
        $this->loadViewsFrom(__DIR__ . "/../resources/views/grid", 'grid');
        
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'VD');

        $this->publishes([
            __DIR__ . '/../resources/views/adminlte' => resource_path('views/vendor/adminlte'),
            __DIR__ . '/../resources/views/grid' => resource_path('views/vendor/grid'),
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
        //set up facade
        AliasLoader::getInstance()->alias('Input', 'Illuminate\Support\Facades\Input');
		AliasLoader::getInstance()->alias('Form', 'Collective\Html\FormFacade');
		AliasLoader::getInstance()->alias('HTML', 'Collective\Html\HtmlFacade');
		AliasLoader::getInstance()->alias('Grids', 'Nayjest\Grids\Grids');
		AliasLoader::getInstance()->alias('FormBuilder', 'Kris\LaravelFormBuilder\Facades\FormBuilder');
    }
}
