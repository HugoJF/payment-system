<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SteamItem extends Model
{
	protected $fillable = [
		'market_hash_name',
		'price',
		'icon_url',
		'index',
	];
}