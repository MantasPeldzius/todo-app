<?php

namespace App\Providers;

// use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot() {
		Gate::define('access-admin', function ($user) {
			return $user->role === 1;
		});
		Gate::define('access-user', function ($user) {
			return $user->role === 2;
		});
    	$this->app['auth']->viaRequest('api', function ($request) {
    	});
    }
    
}
