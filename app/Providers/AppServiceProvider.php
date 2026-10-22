<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Let controllers type-hint ServerRequestInterface (PSR-7) instead of Laravel's Request.
        $this->app->bind(ServerRequestInterface::class, function ($app) {
            $psr17Factory = new Psr17Factory();
            $psrHttpFactory = new PsrHttpFactory(
                $psr17Factory,
                $psr17Factory,
                $psr17Factory,
                $psr17Factory,
            );

            return $psrHttpFactory->createRequest($app->make(Request::class));
        });
    }

    public function boot(): void
    {
        // Plain-HTML pagination links, styled in public/css/app.css.
        Paginator::defaultView('pagination::semantic-ui');
        Paginator::defaultSimpleView('pagination::semantic-ui');
    }
}
