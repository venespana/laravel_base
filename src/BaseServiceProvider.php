<?php

namespace VD;

use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
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
        $this->blade();
        
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

    private function blade()
    {
        $this->loadViewsFrom(__DIR__ . "/../resources/views/adminlte", 'adminlte');
        $this->loadViewsFrom(__DIR__ . "/../resources/views/grid", 'grid');

        $path = __DIR__ . "/../resources/views/adminlte/components";
        foreach (glob("{$path}/*.php") as $file) {
            $file = str_replace("{$path}/", '', $file);
            $name = str_replace('.blade', '', str_replace('.php', '', $file));
            Blade::component("adminlte::components.{$name}", $name);
        }
        $path = __DIR__ . "/../Blade/Directives";
        foreach (glob("{$path}/*.php") as $file) {
            include_once($file);
        }
    }
}
