<?php

namespace App\Jobs;

use App\Order;
use App\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOrderPaidWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $service;

    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->service = app(OrderService::class);
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->order->webhook_url) {
            $this->sendWebhook($this->order);
        }
    }

    protected function sendWebhook(Order $order)
    {
        $data = ['order' => $order->toArray()];

        $this->service->sendWebhook($order, 'order_paid', $data);
    }
}
