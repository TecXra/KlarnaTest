<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-user', function($user) {
            return $user->user_type_id == 5;
        });

        // Use in controller in following way
        // use Gate;
        // if (Gate::denies('admin-user')) {
        //      abort(403, 'Sorry....not really sorry.....at all');
        // }
        // 
        // OR
        // 
        // // if (Gate::allows('admin-user')) {
        //      abort(403, 'Sorry....not really sorry.....at all');
        // }
        // 
        // OR
        // 
        // $this->authorize('admin-user');
        // 
        // OR directly in blade
        // 
        // @can('admin-user')
        //      show something
        // $endcan
        // 
        // OR in Controller with the can method
        // 
        // if( Auth::user->cannot('admin-user')) {
        // if( Auth::user->can('admin-user')) {
        //  ... 
        // }
    }
}
