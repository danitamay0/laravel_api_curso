<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransaction extends ApiController
{
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product,  User $buyer)
    {
        //
        $rules=[
            'quantity'=>'required|integer|min:1'
        ];
        $this->validate($request,$rules);
   

        if($product->seller_id == $buyer->id){
            return $this->errorResponse('no puedes comprar tu mismo producto',409);
        }

        if(!$buyer->esVerificado()){
            return $this->errorResponse('el comprador debe estar verificado',409);
        }
       
        if(!$product->seller->esVerificado()){
            return $this->errorResponse('el vendedor debe estar verificado',409);
        }
        if($request->quantity > $product->quantity){
            return $this->errorResponse('no hay suficientes productos para la solicitud',409);
        }

        //transaccion asegura que  todos los cambias se hagan si la funcion es hecha satisfactoriamente
        //si no los cambios se revierten

        return DB::transaction(function () use ($request,$product,$buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity'=>$request->quantity,
                'product_id'=>$product->id,
                'buyer_id'=>$buyer->id
            ]);

            return $this->showOne($transaction);

        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
  
}
