<?php

namespace App\Http\Controllers\Seller;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;

use App\Product;
use App\Seller;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        //
        $products=$seller->products;
        return response()->json($products);
    }

 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        //
        $reglas=[
            'name'=>'required',
            'quantity'=>'integer|required|min:1',
            'description'=>'required',
            'img'=>'required|image'
        ];

        $this->validate($request,$reglas);
        $data=$request->all();
        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $data['img'] = $request->img->store('');
        $data['seller_id'] = $seller->id;

        $product= Product::create($data);
        return $this->showOne($product);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        //
        $this->verificarVendedor($seller,$product);

        $reglas=[
            'img'=>'image',
            'quantity'=>'integer|min:1',
            'status'=>'in:'.Product::PRODUCTO_DISPONIBLE . ',' . Product::PRODUCTO_NO_DISPONIBLE,
        ];
        $this->validate($request,$reglas);


        
       $product->fill($request->only('name','description','quantity'));
       
     
        if ($request->has('status')) {
            $product->status=$request->status;

            if ($product->estaDisponible() && $product->categories()->count()==0) {
                return $this->errorResponse('el producto debe contar con almenos una categoria para estar activo',409);
            }
        }
     
        if ($request->hasFile('img')) {
            Storage::delete($product->img);
            $product->img=$request->img->store('');
            
        }

        if($product->isClean()){
            return $this->errorResponse('se debe especificar al menos un valor',409);
        }
        $product->save();
        return $this->showOne($product);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->verificarVendedor($seller,$product);

        Storage::delete($product->img);
        $product->delete();
        return $this->showOne($product);
    }

    private function verificarVendedor(Seller $seller, Product $product){
        if(!$seller->id == $product->seller_id){
           throw  new HttpException(429,'el producto no le corresponde a este vendedor');
        }
    }
}
