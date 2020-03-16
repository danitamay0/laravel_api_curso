<?php

namespace App;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
        protected $table='categories';
        
    //modelo categoria
    protected $fillable=[
        'name',
        'description'
    ];

    protected $hidden=[
        'pivot'
    ];
    public function products(){
        return $this->belongsToMany(Product::class);
    }
}
