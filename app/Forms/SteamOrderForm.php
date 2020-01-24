<?php

namespace App\Forms;

use App\SteamOrder;
use Kris\LaravelFormBuilder\Form;

class SteamOrderForm extends Form
{
    public function buildForm()
    {
        $this->add('tradeoffer_state', 'select', [
            'choices' => [
                ''                                     => '=== Select Tradeoffer state ===',
                SteamOrder::INVALID                    => 'INVALID',
                SteamOrder::ACTIVE                     => 'ACTIVE',
                SteamOrder::ACCEPTED                   => 'ACCEPTED',
                SteamOrder::COUNTERED                  => 'COUNTERED',
                SteamOrder::EXPIRED                    => 'EXPIRED',
                SteamOrder::CANCELED                   => 'CANCELED',
                SteamOrder::DECLINED                   => 'DECLINED',
                SteamOrder::INVALID_ITEMS              => 'INVALID_ITEMS',
                SteamOrder::CREATED_NEEDS_CONFIRMATION => 'CREATED_NEEDS_CONFIRMATION',
                SteamOrder::CANCELED_BY_SECOND_FACTOR  => 'CANCELED_BY_SECOND_FACTOR',
                SteamOrder::IN_ESCROW                  => 'IN_ESCROW',
            ],
        ]);
    }
}
