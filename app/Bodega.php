<?php

namespace App;
use App\Producto;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    //
    public function productos(){
        return $this->BelongsToMany(Producto::class);
    }
    protected $hidden=[
        'created_at',
        'updated_at'
    ];
}
