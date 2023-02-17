<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function filter(array $filters): Builder
    {
        collect($filters)->filter(function ($args, $method) {
            return method_exists($this, $method);
        })->each(function ($args, $method) {
            $this->call($method, $args);
        });

        return $this;
    }

    protected function call($method, $args): mixed
    {
        if (is_array($args)) {
            return $this->$method($args[0], $args[1]);
        }

        return $this->$method($args);
    }
}
