<?php

namespace App\Events;

use App\SteamOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TradeofferUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $steamOrder;

	/**
	 * Create a new event instance.
	 *
	 * @param SteamOrder $steamOrder
	 */
    public function __construct(SteamOrder $steamOrder)
    {
    	$this->steamOrder = $steamOrder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('steam-tradeoffers');
    }

	public function broadcastAs()
	{
		return (string) $this->steamOrder->tradeoffer_id;
	}
}
