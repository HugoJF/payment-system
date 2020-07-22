<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'                 => 'email|nullable',
            'reason'                => 'required',
            'return_url'            => 'required',
            'cancel_url'            => 'required',
            'webhook_url'           => 'nullable|string',
            'view_url'              => 'nullable|string',
            'preset_amount'         => 'required|numeric',
            'preset_units'          => 'required|numeric',
            'unit_price'            => 'required|numeric',
            'unit_price_limit'      => 'required|numeric',
            'discount_per_unit'     => 'required|numeric',
            'min_units'             => 'required|numeric',
            'max_units'             => 'required|numeric',
            'avatar'                => 'string|nullable',
            'payer_steam_id'        => 'string|nullable',
            'payer_tradelink'       => 'string|nullable',
            'product_name_singular' => 'string|nullable',
            'product_name_plural'   => 'string|nullable',
        ];
    }
}
