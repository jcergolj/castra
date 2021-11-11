<?php

namespace App\QueryBuilders;

trait Filterable
{
    /**
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filter(array $filters)
    {
        collect($filters)->filter(function ($args, $method) {
            return method_exists($this, $method);
        })->each(function ($args, $method) {
            $this->call($method, $args);
        });

        return $this;
    }

    /**
     * @param  mixed  $method
     * @param  mixed  $args
     * @return mixed
     */
    protected function call($method, $args)
    {
        if (is_array($args)) {
            return $this->$method($args[0], $args[1]);
        }

        return $this->$method($args);
    }
}
