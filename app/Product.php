<?php

namespace App;
use App\Category;
use App\Seller;
use App\Transaction ;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $dates=['deleted_at'];
    const PRODUCTO_DISPONIBLE='disponible';
    const PRODUCTO_NO_DISPONIBLE='no disponible';
    protected $fillable=[
        'name',
        'description',
        'quantity',
        'status',//disponible o no disponible
        'img',
        'seller_id'
    ];
    protected $hidden=[
        'pivot'
    ];
     //funcion de clase para verificar si el objeto tiene la clase disponible
    public function estaDisponible(){
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function categories(){
        //un producto puede estar en muchas categorias por ende la relacion dicha 
        return $this->belongsToMany(Category::class);
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
