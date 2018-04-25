<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ArticleEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $article;
    public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($article, $type)
    {
        $this->article = $article;
        $this->type = $type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('article-channel');
    }

    /**
    * Get the data to broadcast.
    *
    * @return array
    */
    public function broadcastWith()
    {
        $params = [
            'type' => $this->type
        ];

        return array_merge($this->article->toArray(), $params);
    }
}
