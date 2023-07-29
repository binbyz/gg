<?php

namespace Beaverlabs\Gg;

use Illuminate\Database\Query\Builder;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class GgServiceProvider extends ServiceProvider
{
    protected array $macros = [
        Builder::class => Macros\QueryBuilder::class,
        Collection::class => Macros\Collection::class,
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
            $class::macro('gg', (new $macro)->register());
        }
    }

    protected function bindExceptionWatcher(): self
    {
        Event::listen(MessageLogged::class, static function (MessageLogged $logged) {
            if (\array_key_exists('exception', $logged->context) && $logged->context['exception'] instanceof \Throwable) {
                \gtrace($logged->context['exception']);
            }
        });

        return $this;
    }
}
