<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyers=Buyer::has('transactions')->get();

        return $this->showAll($buyers);
    }

   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    { //normalmente no se puede implementar una injection implicita debido que este metodo hereda de
        #users ahora, cada vez que nosotros determinamos un comprador, es por que tiene transacciones,
        #esa consulta se puede hacer de manera global construllendola para todas, se llama GLOBAL SCOPES

        return $this->showOne($buyer);
    }

}
