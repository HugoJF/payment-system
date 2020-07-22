<?php

namespace App\Console\Commands;

use App\Order;
use Exception;
use Illuminate\Config\Repository;
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
     * @var Repository
     */
    private $rechecks;

    /**
     * Create a new command instance.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->rechecks = config('payment-system.rechecking-periods');

        if (count($this->rechecks) === 0) {
            throw new Exception("There are no rechecking periods available!");
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $maxRechecks = count($this->rechecks);

        // TODO: update database to allow unpaid scope
        $pendingOrders = Order::query()
                              ->unpaid()
                              ->lessRechecksThan($maxRechecks)
                              ->cursor();

        foreach ($pendingOrders as $order) {
            if ($this->shouldRecheck($order)) {
                $this->recheckOrder($order);
            }
        }
    }

    private function shouldRecheck(Order $order)
    {
        $delta = $this->rechecks[ $order->recheck_attempts ];
        $current = now()->diffInSeconds($order->updated_at, true);

        $this->info("Checking if order $order->id needs recheck $current (current) > $delta (delta)");

        return $current > $delta;
    }

    private function recheckOrder(Order $order)
    {
        info("Rechecking order $order->id");

        $order->recheck();
    }
}
