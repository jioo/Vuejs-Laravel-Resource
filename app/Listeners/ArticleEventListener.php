<?php

namespace App\Listeners;

use App\Events\ArticleEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticleEventListener
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
     * @param  ArticleEvent  $event
     * @return void
     */
    public function handle(ArticleEvent $event)
    {
        //
    }
}
