<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
{
    $this->registerPolicies();

    Gate::define('is-applicant', function ($user) {
        return $user->role === 'applicant';
    });

    Gate::define('is-mpdo', function ($user) {
        return $user->role === 'mpdo';
    });

    Gate::define('is-meo', function ($user) {
        return $user->role === 'meo';
    });

    Gate::define('is-bfp', function ($user) {
        return $user->role === 'bfp';
    });

    Gate::define('is-admin', function ($user) {
        return $user->role === 'admin';
    });

    Gate::define('mpdo-complete', function ($user) {
        $app = DB::table('applications')
            ->where('applicant_id', $user->id)
            ->first();

        return $app && (
            (isset($app->mpdo_verified) && $app->mpdo_verified == 1) ||
            (isset($app->mpdo_status) && $app->mpdo_status == 'verified')
        );
    });

    Gate::define('meo-complete', function ($user) {
        $app = DB::table('applications')
            ->where('applicant_id', $user->id)
            ->first();

        return $app && (
            (isset($app->meo_verified) && $app->meo_verified == 1) ||
            (isset($app->meo_status) && $app->meo_status == 'verified') ||
            (isset($app->meo_endorsed) && $app->meo_endorsed == 1)
        );
    });
}
}