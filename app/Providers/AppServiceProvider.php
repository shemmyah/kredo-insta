<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        
        //gate - a way to check if a user is allowed to do something
        Gate::define('admin', function($user){
            //name of gate is 'admin'
            return $user->role_id === User::ADMIN_ROLE_ID;
            //$user - is an instance of the User Model
        });
    }
}
