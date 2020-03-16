<?php

namespace App\Http\Controllers;

use App\Bodega;
use App\Product;
use App\Producto;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
class ProductosController extends Controller
{
    //
    public function show(Request $request){
       $bodegas= DB::table('bodega_producto')
        ->join('productos','bodega_producto.producto_id','=','productos.id')
        ->join('bodegas','bodega_producto.bodega_id','=','bodegas.id')
        ->select('bodega_producto.cantidad','productos.nombre as nombreProducto','productos.id as producto_id','bodegas.nombre as nombreBodega','bodegas.id as bodega_id')
        ->get();
        
        return response()->json($bodegas);
    }

    public function store(Request $request,Bodega $bodega){

        $reglas=[
            'nombre'=>'required',
            'cantidad'=>'integer|required',
        ];
        $this->validate($request,$reglas);


       $producto=Producto::where('nombre',$request->nombre)->first();
       
      
      
       if($producto!=null && $producto!= '' && $producto){

         if($producto->bodegas()->find($bodega->id) ){
                 return   response()->json(['err'=>'ya se encuentra el producto con la bodega'],409);
            }
            $producto->bodegas()->save($bodega,[
                'cantidad'=>$request->cantidad
            ]);
            return response()->json(['nombreProducto'=>$producto->nombre,
        'producto_id'=>$producto->id,
        'nombreBodega'=>$bodega->nombre,
        'bodega_id'=>$bodega->id,
        'cantidad'=>$request->cantidad]);
         }
        $producto=new Producto();
        $producto->nombre=$request->nombre;
        $producto->save();

        $producto->bodegas()->save($bodega,[
            'cantidad'=>$request->cantidad
        ]);
        
        return response()->json(['nombreProducto'=>$producto->nombre,
        'producto_id'=>$producto->id,
        'nombreBodega'=>$bodega->nombre,
        'bodega_id'=>$bodega->id,
        'cantidad'=>$request->cantidad]);
        

    }
    public function gestionar(Request $request,Bodega $origen, Bodega $destino){
        $producto=Producto::find($request->producto_id);
        if ($origen->id == $destino->id) {
            return response()->json('no se puede cambiar en la misma bodega');
        }

        $cOrigen=$producto->bodegas()->find($origen->id);
        if (!$cOrigen) {
            return response()->json('no existe  esa bodega');  
        }
      
        $cantidadRestada=$cOrigen->info->cantidad - $request->cantidadADestino;
   
        if ($cantidadRestada < 0) {
            return response()->json('no hay cantidades suficientes para esa bodega');  
        }
       
        $producto->bodegas()->updateExistingPivot($origen->id, ['cantidad'=>$cantidadRestada]);
       

        $Cdestino=$producto->bodegas()->find($destino->id);
        
        $cantidadSumada=$Cdestino->info->cantidad + $request->cantidadADestino ;
        
        $producto->bodegas()->updateExistingPivot($destino->id, ['cantidad'=>$cantidadSumada]);
        return response()->json($producto->bodegas);
        


    }
   
}
