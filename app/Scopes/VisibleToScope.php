<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class VisibleToScope implements Scope
{
    public function __construct(protected string $field)
    {
    }

    public function apply(Builder $builder, Model $model): Builder
    {
        if (user() === null || user()->isAdmin()) {
            return $builder;
        }

        return $builder->where($this->field, user()->id);
    }
}
