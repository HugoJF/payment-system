<?php

namespace App\Console\Commands;

use App\Order;
use App\SteamOrder;
use Illuminate\Console\Command;

class RefreshActiveSteamOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steamorders:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Querying database for pending Steam Orders');
        $pendingOrders = SteamOrder::whereIn('tradeoffer_state', [SteamOrder::ACTIVE, SteamOrder::IN_ESCROW])->get();

        $this->refreshOrders($pendingOrders);
    }

    /**
     * @param $pendingOrders
     */
    protected function refreshOrders($pendingOrders): void
    {
        $this->info("Found {$pendingOrders->count()} pending orders...");
        foreach ($pendingOrders as $order) {
            $this->refreshOrder($order);
        }
    }

    /**
     * @param $order
     */
    protected function refreshOrder($order): void
    {
        if ($this->tradeShouldBeCanceled($order)) {
            $order->cancel();

            $this->warn('Cancelling order #' . $order->base->id . ' as it expired!');
        } else {
            $order->recheck();

            $this->info("Refreshing order #{$order->base->id}");
        }
    }

    protected function tradeShouldBeCanceled(SteamOrder $order)
    {
        return $this->orderIsActive($order) && $this->tradeofferIsTooOld($order);
    }

    protected function orderIsActive(SteamOrder $order)
    {
        return $order->tradeoffer_state === SteamOrder::ACTIVE;
    }

    protected function tradeofferIsTooOld(SteamOrder $order)
    {
        $delta = $order->tradeoffer_sent_at->diffInMinutes();

        $this->info("Order #{$order->base->id} is $delta minutes old.");

        return $delta > config('steam.tradeoffer_expiration', 30);
    }
}
