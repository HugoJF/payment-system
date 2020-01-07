<?php

namespace App\Console\Commands;

use App\Order;
use Illuminate\Console\Command;

class RefreshPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:recheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rechecks any pending orders';
    /**
     * @var \Illuminate\Config\Repository
     */
    private $rechecks;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->rechecks = config('payment-system.rechecking-periods');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $maxRechecks = count($this->rechecks);

        $pendingOrders = Order::query()
            ->whereColumn('paid_amount', '<', 'preset_amount')
            ->where('recheck_attempts', '<', $maxRechecks)
            ->cursor();

        foreach ($pendingOrders as $order) {
            if ($this->shouldRecheck($order))
                $this->recheckOrder($order);
        }
    }

    private function shouldRecheck(Order $order)
    {
        $delta = $this->rechecks[$order->recheck_attempts];
        $current = now()->diffInSeconds($order->updated_at, true);

        return $current > $delta;

    }

    private function recheckOrder(Order $order)
    {
        $order->recheck();
    }
}
