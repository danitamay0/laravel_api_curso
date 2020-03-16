<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $sellers= Seller::has('products')->get();

       return $this->showAll($sellers);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {   //normalmente no se puede implementar una injection implicita debido que este metodo hereda de
        #users ahora, cada vez que nosotros determinamos un comprador, es por que tiene transacciones,
        #esa consulta se puede hacer de manera global construllendola para todas, se llama GLOBAL SCOPES

        return $this->showOne($seller);

    }

 }
