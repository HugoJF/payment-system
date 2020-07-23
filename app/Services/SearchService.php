<?php

namespace App\Services;

use App\Order;
use Illuminate\Support\Collection;
use Spatie\Searchable\Search;

class SearchService
{
    public function search(string $term)
    {
        $search = (new Search)
            ->registerModel(Order::class, 'id', 'reason', 'return_url',
                'cancel_url', 'avatar', 'payer_steam_id',
                'payer_tradelink', 'orderable_type');

        $result = $search->search($term);

        // Pluck Models from each type group
        return $result->groupByType()->mapWithKeys(function (Collection $items, $type) {
            return [$type => $items->pluck('searchable')];
        });
    }
}
