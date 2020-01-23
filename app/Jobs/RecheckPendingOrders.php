<?php

namespace App\Jobs;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RecheckPendingOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $waitingPeriods;

    public function __construct()
    {
        $this->waitingPeriods = config('payment-system.rechecking-periods');
    }

    public function handle()
    {
        $pendingOrders = Order::unpaid()->get();

        info("Found {$pendingOrders->count()} pending orders...");

        /** @var Order $order */
        foreach ($pendingOrders as $order) {
            // Check if order is old enough to be rechecked
            if ($this->shouldRecheck($order)) {
                info("Rechecking order {$order->id}");
                $order->recheck();
            }

            // Log if state changed
            if ($order->paid)
                info("Order $order->id was detected as paid.");
        }
    }

    protected function shouldRecheck(Order $order)
    {
        // How many recheck attempts this order has
        $attempts = $order->recheck_attempts;

        // What index should we check for time diff
        $index = $this->clamp($attempts, 0, count($this->waitingPeriods) - 1);

        // The wait time for this order
        $time = $this->waitingPeriods[ $index ];

        return $order->updated_at->diffInSeconds() > $time;
    }

    protected function clamp($value, $min, $max)
    {
        return max(min($max, $value), $min);
    }
}
