<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //:start-bindings:
        $this->app->bind(\App\Repositories\Contracts\GlossaryRepository::class, \App\Repositories\GlossaryRepositoryEloquent::class);
        $this->app->bind(\App\Services\Contracts\GlossaryService::class, \App\Services\GlossaryService::class);
        $this->app->bind(\App\Repositories\Contracts\PostRepository::class, \App\Repositories\PostRepositoryEloquent::class);
        $this->app->bind(\App\Services\Contracts\PostService::class, \App\Services\PostService::class);
        //:end-bindings:
    }
}
