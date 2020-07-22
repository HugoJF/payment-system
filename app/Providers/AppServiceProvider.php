<?php

namespace App\Providers;

use App\MPOrder;
use App\Observers\MPOrderObserver;
use App\Observers\OrderObserver;
use App\Observers\OrderOverpaymentObserver;
use App\Observers\PayPalOrderObserver;
use App\Observers\SteamOrderObserver;
use App\Order;
use App\PayPalOrder;
use App\SteamOrder;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
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
		$this->registerViewComposers();
	}

	protected function registerObservers(): void
	{
		Order::observe(OrderObserver::class);
		Order::observe(OrderOverpaymentObserver::class);
		MPOrder::observe(MPOrderObserver::class);
		PayPalOrder::observe(PayPalOrderObserver::class);
		SteamOrder::observe(SteamOrderObserver::class);
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

    protected function registerViewComposers()
    {
        View::composer('*', function ($view) {
            $view->with('pusherAppKey', config('broadcasting.connections.pusher.key'));
        });
    }
}
