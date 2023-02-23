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
        $field = str($name);
        if (empty($value)) {
            $this->forceFill([
                $field->append('_continent_id') => null,
                $field->append('_country_id')   => null,
                $field->append('_province_id')  => null,
                $field->append('_city_id')      => null
            ]);
        }

        $place = Place::for($value)->first();
        if (!empty($place)) {
            $this->forceFill([
                $field->append('_continent_id') => $place->continent_id,
                $field->append('_country_id')   => $place->country_id,
                $field->append('_province_id')  => $place->province_id,
                $field->append('_city_id')      => $place->city_id
            ]);
        }
    }
}
