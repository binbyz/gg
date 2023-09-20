<?php

namespace Beaverlabs\Gg\Providers;

use Beaverlabs\Gg\Gg;
use Beaverlabs\Gg\Macros\QueryBuilder as QueryBuilderMacro;
use Beaverlabs\Gg\Macros\Collection as CollectionMacro;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class GgServiceProvider extends ServiceProvider
{
    protected array $macros = [
        Builder::class => QueryBuilderMacro::class,
        Collection::class => CollectionMacro::class,
    ];

    public function register()
    {
        $this->app->singleton(Gg::class, static function () {
            return new Gg();
        });
    }

    public function boot()
    {
        $this->bootConfig();
        $this->bootMacros();
    }

    private function bootMacros()
    {
        foreach ($this->macros as $class => $macro) {
            if (\method_exists($class, 'macro')) {
                $class::macro('gg', (new $macro)->register());
            }
        }
    }

    private function bootConfig()
    {
        $this->publishes([__DIR__ . '/../config/gg.php' => config_path('gg.php')], 'config');
    }
}
