<?php

namespace App\Providers;

use App\Domain\Course;
use App\Domain\CourseId;
use App\Domain\Membership;
use App\Domain\RoleId;
use App\Domain\User;
use App\Http\Resources\CourseResource;
use App\Http\Resources\MembershipResource;
use App\Http\Resources\UserResource;
use App\Policies\CoursePolicy;
use App\Policies\MembershipPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
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
        Course::class => CoursePolicy::class,
        CourseResource::class => CoursePolicy::class,
        CourseId::class => CoursePolicy::class,
        User::class => UserPolicy::class,
        UserResource::class => UserPolicy::class,
        RoleId::class => RolePolicy::class,
        Membership::class => MembershipPolicy::class,
        MembershipResource::class => MembershipPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
