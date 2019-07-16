<?php

namespace App\Jobs;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class RecheckPendingOrders implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private $waitingPeriods;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->waitingPeriods = config('rechecking.periods');
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$pendingOrders = Order::wherePaid(false)->get();

		Log::info("Found {$pendingOrders->count()} pending orders...");

		foreach ($pendingOrders as $order) {
			// Check if order is old enough to be rechecked
			if ($this->shouldRecheck($order)) {
				Log::info("Rechecking order {$order->id}");
				$order->recheck();
			}

			// Log if state changed
			if ($order->paid)
				Log::info("Order $order->id was detected as paid.");
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

		return $order->updated_at->diffInMilliseconds() > $time;
	}

	protected function clamp($value, $min, $max)
	{
		return max(min($max, $value), $min);
	}
}
