<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FranchiseScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->where($model->getTable().'.franchise_id', current_user_franchise_id());
    }
}
