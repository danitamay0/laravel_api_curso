<?php

namespace App;
use App\Transaction;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Scopes\BuyerScope;
class Buyer extends User
{   
    protected static function boot()
    {   #heredamos el boot o constructor padre
        parent::boot();
        #instanciamos nuestro BuyerScope creado previamente con la consulta
        static::addGlobalScope(new BuyerScope);
    }
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    
}
