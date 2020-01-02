<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/17/2019
 * Time: 1:54 AM
 */

namespace App;

use App\Http\Controllers\MPOrderController;
use App\Http\Controllers\PayPalOrderController;
use App\Http\Controllers\SteamOrderController;

class OrderService
{
	private $classMap = [
		Order::PAYPAL      => PayPalOrder::class,
		Order::STEAM       => SteamOrder::class,
		Order::MERCADOPAGO => MPOrder::class,
	];

	private $controllerMap = [
		PayPalOrder::class => PayPalOrderController::class,
		SteamOrder::class  => SteamOrderController::class,
		MPOrder::class     => MPOrderController::class,
	];

	public function make(array $data)
	{
		$order = Order::make();

		$order->fill($data);

		$order->save();

		return $order;
	}

	public function update(Order $order, array $data)
	{
		$order->fill($data);
		if ($order->type() && in_array('orderable', $data))
			$order->orderable->fill($data['orderable']);

		$order->save();
		$order->orderable->save();
	}

	public function getControllerByClass($class)
	{
		return $this->controllerMap[ $class ] ?? null;
	}

	public function getClassByType($type)
	{
		return $this->classMap[ $type ] ?? null;
	}

	public function getControllerByType($type)
	{
		return $this->getControllerByClass($this->getClassByType($type));
	}

    public function calculateUnits(Order $base, $value)
    {
        $perUnit = $base->unit_price;
        $units = 0;

        while ($value >= $perUnit) {
            $units++;
            $value -= $perUnit;
            $perUnit -= $base->discount_per_unit;

            if ($perUnit < $base->unit_price_limit)
                $perUnit = $base->unit_price_limit;
        }

        return $units;
	}
}
