<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
	protected $fillable = [
		'market_hash_name',
		'price',
		'icon_url',
		'name_color',
		'quality_color',
		'rarity_color',
		'index',
	];
}
