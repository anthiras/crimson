<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Domain\CourseRepository', 'App\Persistence\DbCourseRepository');
        $this->app->singleton('App\Domain\UserRepository', 'App\Persistence\DbUserRepository');
        $this->app->singleton('App\Domain\MembershipRepository', 'App\Persistence\DbMembershipRepository');

        $this->app->singleton('App\Queries\CourseQuery', 'App\Persistence\DbCourseQuery');
        $this->app->singleton('App\Queries\MembershipQuery', 'App\Persistence\DbMembershipQuery');
        $this->app->singleton('App\Queries\UserQuery', 'App\Persistence\DbUserQuery');
        $this->app->singleton('App\Queries\RoleQuery', 'App\Persistence\DbRoleQuery');
        $this->app->singleton('App\Queries\CourseParticipantQuery', 'App\Persistence\DbCourseParticipantQuery');

        $this->app->bind(
            \Auth0\Login\Contract\Auth0UserRepository::class,
            \Auth0\Login\Repository\Auth0UserRepository::class
        );
    }
}
