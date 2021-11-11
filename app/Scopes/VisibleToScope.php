<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class VisibleToScope implements Scope
{
    /** @var string */
    protected $field;

    public function __construct($field)
    {
        $this->field = $field;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (user() === null || user()->isAdmin()) {
            return $builder;
        }

        return $builder->where($this->field, user()->id);
    }
}
