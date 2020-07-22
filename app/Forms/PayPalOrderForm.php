<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class PayPalOrderForm extends Form
{
    public function buildForm()
    {
        $this->add('status', 'select', [
            'choices' => collect([
                'None',
                'Canceled-Reversal',
                'Completed',
                'Denied',
                'Expired',
                'Failed',
                'In-Progress',
                'Partially-Refunded',
                'Pending',
                'Refunded',
                'Processed',
                'Voided',
            ])->mapWithKeys(function ($v) {
                return [$v => $v];
            })->toArray(),
        ]);
    }
}
