<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;

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

        View::composer('*', function ($view) {
            if (Auth::check()) {
                // Count unread messages where the current user is the recipient
                $unreadCount = Message::where('is_read', false)
                    ->where('sender_id', '!=', Auth::id())
                    ->whereHas('chatRoom', function($query) {
                        $query->where('user_one_id', Auth::id())
                              ->orWhere('user_two_id', Auth::id());
                    })
                    ->count();

                $view->with('unreadCount', $unreadCount);
            }
        });
    }
}
