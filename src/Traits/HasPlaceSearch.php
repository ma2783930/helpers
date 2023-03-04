<?php

namespace Helpers\Traits;

use Helpers\Models\View\Place;
use Illuminate\Support\Collection;

trait HasPlaceSearch
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function searchPlace($keyword): Collection
    {
        validator()
            ->make(
                compact('keyword'),
                ['keyword' => 'required|persian_alpha_eng_num']
            )
            ->validate();

        return Place::ofSelectable()
                    ->search($keyword)
                    ->take(100)
                    ->get()
                    ->map(fn(Place $place) => [
                        'id'   => $place->id,
                        'name' => $place->display_name
                    ]);
    }
}
