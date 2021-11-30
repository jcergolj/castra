<?php

namespace App\QueryBuilders;

class UserQueryBuilder extends QueryBuilder
{
    /**
     * Filter by email field.
     *
     * @param  string|null  $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search($term = null)
    {
        $this->when($term, function ($query, $term) {
            $query->where('email', 'LIKE', "%{$term}%");
        });

        return $this;
    }

    /**
     * Filter by role.
     *
     * @param  string|null  $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function role($role = null)
    {
        $this->when($role, function ($query, $role) {
            $query->where('role', $role);
        });

        return $this;
    }
}
