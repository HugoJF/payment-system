<?php

namespace App\Providers;

use App\Observers\OrderObserver;
use App\Order;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Schema::defaultStringLength(191);

		$this->registerObservers();
		$this->registerCustomRouteBindings();
		$this->registerIdeHelper();
	}

	protected function registerObservers(): void
	{
		Order::observe(OrderObserver::class);
	}

	protected function registerCustomRouteBindings(): void
	{
		//
	}

	protected function registerIdeHelper(): void
	{
		if ($this->app->environment() !== 'production') {
			$this->app->register(IdeHelperServiceProvider::class);
		}
	}
}
