<?php

namespace App;

use App\Contracts\OrderContract;
use App\Services\PayPal\PayPalRecheckService;
use Illuminate\Database\Eloquent\Model;

class PayPalOrder extends Model implements OrderContract
{
    protected $table = 'paypal_orders';

    protected $fillable = ['status'];

    /*****************
     * RELATIONSHIPS *
     *****************/

    public function base()
    {
        return $this->morphOne('App\Order', 'orderable');
    }

    public function paymentCompleted()
    {
        return strcasecmp($this->status, 'PaymentActionCompleted') === 0;
    }

    /**************
     * OVERWRITES *
     **************/

    /**
     * @throws \Exception
     * @deprecated
     */
    public function recheck()
    {
        /** @var PayPalRecheckService $service */
        $service = app(PayPalRecheckService::class);

        $service->handle($this);
    }

    public function type()
    {
        return self::class;
    }

    public function canInit($order)
    {
        return true;
    }

    public function fixedPricing()
    {
        return true;
    }
}
