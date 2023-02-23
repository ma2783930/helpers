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

    /**
     * @param string $name
     * @return int|null
     */
    public function getPlaceId(string $name): int|null
    {
        $continentId = $this->getAttribute(sprintf('%s_continent_id', $name));
        $countryId   = $this->getAttribute(sprintf('%s_country_id', $name));
        $provinceId  = $this->getAttribute(sprintf('%s_province_id', $name));
        $cityId      = $this->getAttribute(sprintf('%s_city_id', $name));

        if (!empty($cityId)) return $cityId;
        if (!empty($provinceId)) return $provinceId;
        if (!empty($countryId)) return $countryId;
        if (!empty($continentId)) return $continentId;

        return null;
    }

    public function getPlaceName(string $name): string|null
    {
        $countryRelation  = sprintf('%sCountry', $name);
        $provinceRelation = sprintf('%sProvince', $name);
        $cityRelation     = sprintf('%sCity', $name);

        if (!empty($this->{$cityRelation})) {
            return sprintf('%s / %s / %s', $this->{$countryRelation}->name, $this->{$provinceRelation}->name, $this->{$cityRelation}->name );
        }

        if (!empty($this->{$provinceRelation})) {
            return sprintf('%s / %s', $this->{$countryRelation}->name, $this->{$provinceRelation}->name);
        }

        if (!empty($this->{$countryRelation})) {
            return $this->{$countryRelation}->name;
        }

        return null;
    }
}
