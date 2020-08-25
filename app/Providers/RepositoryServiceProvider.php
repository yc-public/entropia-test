<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Library\Repositories\ListRepository;
use App\Library\Repositories\UserRepository;
use App\Library\Repositories\MovieRepository;

use App\Library\Services\ListService;
use App\Library\Services\UserService;
use App\Library\Services\MovieService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ListRepository::class, ListService::class);
        $this->app->bind(UserRepository::class, UserService::class);
        $this->app->bind(MovieRepository::class, MovieService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
