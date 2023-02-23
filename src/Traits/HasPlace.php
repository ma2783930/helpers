<?php

namespace Helpers\Traits;

use Helpers\Models\View\Place;

trait HasPlace
{
    /**
     * @param string $name
     * @param        $value
     * @return void
     */
    public function fillPlace(string $name, $value = null): void
    {
        if (empty($value)) {
            $this->forceFill([
                str($name)->append('_continent_id') => null,
                str($name)->append('_country_id')   => null,
                str($name)->append('_province_id')  => null,
                str($name)->append('_city_id')      => null
            ]);
        }

        $place = Place::for($value)->first();
        if (!empty($place)) {
            $this->forceFill([
                str($name)->append('_continent_id') => $place->continent_id,
                str($name)->append('_country_id')   => $place->country_id,
                str($name)->append('_province_id')  => $place->province_id,
                str($name)->append('_city_id')      => $place->city_id
            ]);
        }
    }
}
