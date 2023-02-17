<?php

namespace App\QueryBuilders;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 * @extends Builder<TModelClass>
 */
class UserQueryBuilder extends Builder
{
    use Filterable;

    public function search(string|null $term = null): Builder
    {
        $this->when($term, function ($query, $term) {
            $query->where('email', 'LIKE', "%{$term}%");
        });

        return $this;
    }

    public function role(string|null $role = null): Builder
    {
        $this->when($role, function ($query, $role) {
            $query->where('role', UserRoles::from($role));
        });

        return $this;
    }
}
