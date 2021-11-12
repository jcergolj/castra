<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

abstract class QueryBuilder extends Builder
{
    /**
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filter(array $filters)
    {
        foreach ($filters as $name => $value) {
            if (! method_exists($this, $name)) {
                continue;
            }

            if (is_array($value)) {
                $this->$name($value[0], $value[1]);
            } else {
                $this->$name($value);
            }
        }

        return $this;
    }

    /**
     * @param  string  $field
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderByField($field, $direction)
    {
        $this->orderBy($field, $direction);

        return $this;
    }
}
