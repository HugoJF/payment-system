<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebhookHistory extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
