<?php

namespace App\Console\Commands;

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
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->info('Querying database for pending Steam Orders');
		$pendingOrders = SteamOrder::whereIn('tradeoffer_state', [SteamOrder::ACTIVE, SteamOrder::IN_ESCROW])->get();
		$this->info("Found {$pendingOrders->count()} pending orders...");

		foreach ($pendingOrders as $order) {
			$order->recheck();
			$this->info("Refreshing order # {$order->base->public_id} with new state: [{$order->tradeoffer_state}] {$order->status()}");

			// TODO: implement
			if ($order->tradeoffer_state === 2 && false && $order->tradeoffer_sent->diffInMinutes() > \Setting::get('expiration-time-min', 30)) {
				$order->cancel();
				$this->warn('Cancelling order #' . $order->baseOrder->public_id . ' as it expired!');
			}
		}
	}
}
