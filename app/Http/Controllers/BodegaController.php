<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bodega;
class BodegaController extends Controller
{
    public function show(){
        return Bodega::all();
    }
}
