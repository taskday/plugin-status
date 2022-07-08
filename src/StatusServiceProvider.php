<?php

namespace Performing\Taskday\Status;

use Illuminate\Support\Facades\Event;
use Taskday\Facades\Taskday;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Taskday\Events\CardCreatedEvent;
use Taskday\Models\Card;

class StatusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Registering extra web routes
        Route::middleware('web')->group(__DIR__ . '/../routes/web.php');

        // Listening for new card and set default value for status
        Event::listen(CardCreatedEvent::class, function (CardCreatedEvent $event) {
            $card = Card::find($event->cardId);
            foreach ($card->project->fields as $field) {
                if ($field->type == 'status') {
                    $options = collect($field->options)->first();
                    $card->setCustom($field, $options['color']);
                }
            }
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register plugin
        Taskday::register(new StatusPlugin, 'status');
    }
}
