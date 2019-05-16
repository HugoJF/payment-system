<?php

namespace App\Console\Commands;

use App\ShopItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class RefreshItemData extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'items:refresh {tfa}';
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generates fresh data from BitSkins API';

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
		$rawBsData = $this->getBitSkinsData($this->argument('tfa'));
		$bsData = collect($rawBsData->prices);
		$this->info("Collected {$bsData->count()} prices");

		$this->info("After filtering: {$bsData->count()}");

		ShopItem::truncate();

		$bsData = $bsData->reject(function ($item) {
			return
				str_contains($item->market_hash_name, 'Sticker') ||
				str_contains($item->market_hash_name, 'Holo') ||
				str_contains($item->market_hash_name, 'Foil') ||
				str_contains($item->market_hash_name, 'Gloves') ||
				str_contains($item->market_hash_name, 'Souvenir');
		});

		$bsData->each(function ($item) {
			preg_match('/(★ )?(StatTrak™)? ?(.*?) \| (.*?) \((.*?)\)/', $item->market_hash_name, $matches);
			$i = ShopItem::make();
			$i->fill((array) $item);
			$i->price = ceil($item->price * 100);
			try {
				if ($matches[2]) {
					$i->stattrak = true;
				} else {
					$i->stattrak = false;
				}
				$i->item_name = trim(($matches[1] ?? '') . ' ' . $matches[3]);
				$i->skin_name = $matches[4];
				$i->condition = $matches[5];
			} catch (\Exception $e) {
				Log::warning("Error while splitting {$item->market_hash_name}");
			}

			try {
				$i->save();
			} catch (\Exception $e) {
				Log::warning("Error while saving item {$item->market_hash_name}", [
					'item' => $item,
				]);
			}
		});
	}

	private function getBitSkinsData($tfa)
	{
		return cache()->remember('bit-skins-api', 60000, function () use ($tfa) {
			$api = config('app.bit-skins-key');
			$result = Curl::to("https://bitskins.com/api/v1/get_all_item_prices/");
			$result->withData([
				'api_key' => $api,
				'code'    => $tfa,
				'app_id'  => 730,
			]);
			$result->asJson();

			return $result->get();
		});
	}
}