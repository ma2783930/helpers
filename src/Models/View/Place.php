<?php

namespace Helpers\Models\View;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property integer continent_id
 * @property integer country_id
 * @property integer province_id
 * @property integer city_id
 * @property string  continent_name
 * @property string  country_name
 * @property string  province_name
 * @property string  city_name
 */
class Place extends Model
{
    protected $table        = 'places';
    public    $incrementing = false;
    protected $primaryKey   = null;

    protected $casts   = [
        'id'           => 'integer',
        'continent_id' => 'integer',
        'country_id'   => 'integer',
        'province_id'  => 'integer',
        'city_id'      => 'integer'
    ];
    protected $appends = ['id'];

    #region Scopes

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfSelectable(Builder $builder): Builder
    {
        return $builder
            ->where(function (Builder $builder) {
                $builder
                    ->whereNotNull(['country_id', 'country_name'])
                    ->whereNull(['province_id', 'province_name', 'city_id', 'city_name']);
            })
            ->orWhere(function (Builder $builder) {
                $builder
                    ->whereNotNull(['country_id', 'country_name', 'province_id', 'province_name'])
                    ->whereNull(['city_id', 'city_name']);
            })
            ->orWhere(function (Builder $builder) {
                $builder->whereNotNull(['country_id', 'country_name', 'province_id', 'province_name', 'city_id', 'city_name']);
            });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int                                   $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFor(Builder $builder, int $id): Builder
    {
        return $builder->where('continent_id', $id)
                       ->orWhere('country_id', $id)
                       ->orWhere('province_id', $id)
                       ->orWhere('city_id', $id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param                                       $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $builder, $keyword): Builder
    {
        return $builder->where('country_name', 'like', "%{$keyword}%")
                       ->orWhere('province_name', 'like', "%{$keyword}%")
                       ->orWhere('city_name', 'like', "%{$keyword}%")
                       ->orderBy('country_name')
                       ->orderBy('province_name')
                       ->orderBy('city_name');
    }

    #endregion

    #region Virtual-Attributes

    /**
     * @return int
     */
    public function getIdAttribute(): int
    {
        if (!empty($this->country_id) && !empty($this->province_id) && !empty($this->city_id)) {
            return $this->city_id;
        }

        if (!empty($this->country_id) && !empty($this->province_id)) {
            return $this->province_id;
        }

        return $this->country_id;
    }

    /**
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        $separator = ' / ';
        return trim(implode($separator, [
            $this->country_name,
            $this->province_name,
            $this->city_name
        ]), $separator);
    }

    /**
     * @return bool
     */
    public function getIsContinentAttribute(): bool
    {
        return !empty($this->continent_id) && empty($this->country_id) && empty($this->province_id) && empty($this->city_id);
    }

    /**
     * @return bool
     */
    public function getIsCountryAttribute(): bool
    {
        return !empty($this->continent_id) && !empty($this->country_id) && empty($this->province_id) && empty($this->city_id);
    }

    /**
     * @return bool
     */
    public function getIsProvinceAttribute(): bool
    {
        return !empty($this->continent_id) && !empty($this->country_id) && !empty($this->province_id) && empty($this->city_id);
    }

    /**
     * @return bool
     */
    public function getIsCityAttribute(): bool
    {
        return !empty($this->continent_id) && !empty($this->country_id) && !empty($this->province_id) && !empty($this->city_id);
    }

    #endregion
}
