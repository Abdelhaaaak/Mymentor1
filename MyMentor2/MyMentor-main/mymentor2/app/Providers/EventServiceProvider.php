<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * By default, this array is empty because the application currently
     * does not use any event‐listener pairs. If you need to dispatch events
     * (e.g., OrderShipped, UserRegistered) and register corresponding listeners,
     * add entries here. Example:
     *
     *   protected $listen = [
     *       \App\Events\OrderShipped::class => [
     *           \App\Listeners\SendShipmentNotification::class,
     *       ],
     *   ];
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // No events to listen for at this time.
        // Future events can be added as key => [listenerClasses].
    ];

    /**
     * Register any events for your application.
     *
     * This method is called after all other service providers have been registered.
     * If you need to manually register event subscribers, model observers, or
     * custom event dispatching logic, you can do so here. Since there are currently
     * no events or observers to register, this method remains intentionally empty.
     *
     * Example of registering a model observer:
     *
     *   \App\Models\User::observe(\App\Observers\UserObserver::class);
     *
     * @return void
     */
    public function boot(): void
    {
        // Intentionally left blank:
        // No additional event bootstrapping or model observers required.
        //
        // To add event subscribers:
        // $this->subscribe(\App\Listeners\UserEventSubscriber::class);
        //
        // To manually register observers:
        // \App\Models\Post::observe(\App\Observers\PostObserver::class);
    }
}
