<?php
namespace App\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;

#scope = alcance
class BuyerScope implements Scope {
    public function apply(Builder $builder,Model $model){
        #builder construlle una consulta 
        $builder->has('transactions');
    }
}