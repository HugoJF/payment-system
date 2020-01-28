<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 7/17/2019
 * Time: 2:06 AM
 */

namespace App\Services;

use App\Classes\MP2;
use App\MPOrder;
use App\Order;
use App\WebhookHistory;
use DB;
use Exception;
use Illuminate\Support\Collection;

class WebhookService
{
    public function createHistory(Order $order, $response)
    {
        $history = WebhookHistory::make();

        $history->status = $response->status;
        $history->response = $response->content;
        $history->error = $response->error ?? null;
        $history->content_type = $response->contentType;

        $history->order()->associate($order);

        $history->save();
    }
}
