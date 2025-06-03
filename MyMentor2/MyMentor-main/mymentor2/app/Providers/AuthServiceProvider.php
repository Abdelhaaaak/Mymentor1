<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * By default, no policies are defined. If you add model‐to‐policy
     * mappings, update this array accordingly. Examples:
     *   \App\Models\Post::class => \App\Policies\PostPolicy::class,
     *   \App\Models\User::class => \App\Policies\UserPolicy::class,
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Example:
        // \App\Models\Post::class => \App\Policies\PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * Laravel will automatically call this method during the framework’s
     * bootstrap process. By default, we invoke $this->registerPolicies() to
     * register any defined policies. If your application does not currently
     * use policies, you may leave this method empty, but include a comment
     * to clarify intent.
     *
     * @return void
     */
    public function boot(): void
    {
        // Register any policies defined in the $policies property.
        $this->registerPolicies();

        // Nothing else to boot at present. If you need to define custom
        // gates, add them here. For example:
        //
        // Gate::define('update-post', function (User $user, Post $post) {
        //     return $user->id === $post->user_id;
        // });
        //
        // Since no policies or gates exist right now, this remains as-is.
    }
}
