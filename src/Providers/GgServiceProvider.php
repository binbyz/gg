<?php

namespace Beaverlabs\Gg\Providers;

use Beaverlabs\Gg\Gg;
use Beaverlabs\Gg\Macros\Collection as CollectionMacro;
use Beaverlabs\Gg\Macros\QueryBuilder as QueryBuilderMacro;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class GgServiceProvider extends ServiceProvider
{
    protected array $macros = [
        Builder::class => QueryBuilderMacro::class,
        Collection::class => CollectionMacro::class,
    ];

    public function register(): void
    {
        $this->app->singleton(Gg::class, static function () {
            return new Gg();
        });
    }

    public function boot(): void
    {
        $this->bootConfig();
        $this->bootMacros();
    }

    private function bootMacros(): void
    {
        foreach ($this->macros as $class => $macro) {
            if (method_exists($class, 'macro')) {
                $class::macro('gg', (new $macro)->register());
            }
        }
    }

    private function bootConfig(): void
    {
        $this->publishes([__DIR__.'/../config/gg.php' => config_path('gg.php')], 'config');
    }
}
