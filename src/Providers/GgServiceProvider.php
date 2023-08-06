<?php

namespace Beaverlabs\Gg\Providers;

use Beaverlabs\Gg\Events\GgRequestEvent;
use Beaverlabs\Gg\Gg;
use Beaverlabs\Gg\Macros\QueryBuilder as QueryBuilderMacro;
use Beaverlabs\Gg\Macros\Collection as CollectionMacro;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
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
        $this->app->bind(Gg::class, static function () {
            return new Gg();
        });
    }

    public function boot()
    {
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
}
