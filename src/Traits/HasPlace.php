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
        $countryId  = $this->getAttribute(str(sprintf('%s_country_id', $name))->trim('_')->camel()->toString());
        $provinceId = $this->getAttribute(str(sprintf('%s_province_id', $name))->trim('_')->camel()->toString());
        $cityId     = $this->getAttribute(str(sprintf('%s_city_id', $name))->trim('_')->camel()->toString());

        if (!empty($cityId)) return $cityId;
        if (!empty($provinceId)) return $provinceId;
        if (!empty($countryId)) return $countryId;

        return null;
    }

    public function getPlaceName(string $name = null): string|null
    {
        $countryRelation  = str(sprintf('%s_country', $name))->trim('_')->camel()->toString();
        $provinceRelation = str(sprintf('%s_province', $name))->trim('_')->camel()->toString();
        $cityRelation     = str(sprintf('%s_city', $name))->trim('_')->camel()->toString();

        if (!empty($this->{$cityRelation})) {
            return sprintf('%s / %s / %s', $this->{$countryRelation}->name, $this->{$provinceRelation}->name, $this->{$cityRelation}->name);
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
