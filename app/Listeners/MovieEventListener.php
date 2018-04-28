<?php

namespace App\Listeners;

use App\Events\MovieEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MovieEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MovieEvent  $event
     * @return void
     */
    public function handle(MovieEvent $event)
    {
        //
    }
}
