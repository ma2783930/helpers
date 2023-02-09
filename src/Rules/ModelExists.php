<?php

namespace Helpers\Rules;

use Carbon\Carbon;
use EloquentTraits\Expirable\Expirable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rules\Exists;

class ModelExists extends Exists
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|\EloquentTraits\Expirable\Expirable
     */
    protected Model $model;

    /**
     * @param $table
     * @param string $column
     * @param bool $withoutExpired
     */
    public function __construct($table, string $column = 'id', bool $withoutExpired = true)
    {
        parent::__construct($table, $column);

        if (is_subclass_of($table, Model::class)) {
            $this->model = new $table;

            if ($withoutExpired && in_array(Expirable::class, class_uses_recursive($this->model))) {
                $this->withoutExpired();
            }
        }
    }

    /**
     * @return $this
     */
    public function withoutExpired(): static
    {
        $this->where(function (Builder $builder) {
            $builder->whereNull($this->model->getExpiredAtColumn())
                    ->orWhere($this->model->getExpiredAtColumn(), '>', Carbon::now());
        });

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyExpired(): static
    {
        $this->where(function (Builder $builder) {
            $builder->whereNotNull($this->model->getExpiredAtColumn())
                    ->where($this->model->getExpiredAtColumn(), '<', Carbon::now());
        });

        return $this;
    }
}
