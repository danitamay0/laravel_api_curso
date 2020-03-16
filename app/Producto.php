<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Bodega;
class Producto extends Model
{
    //
    public function bodegas(){
        return $this->BelongsToMany(Bodega::class)->as('info')->withPivot('cantidad');
    }
}
