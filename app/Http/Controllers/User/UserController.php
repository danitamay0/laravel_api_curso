<?php

namespace App\Http\Controllers\User;

use App\Buyer;
use App\Seller;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Mail\userCreate;
use App\Mail\userMailChanged;
use App\Product;
use App\Transaction;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $users= User::all();
       return $this->showAll($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        $reglas = [
            'email' => 'required|unique:users|email',
            'name' => 'required',
            'password'=>'required|min:6|confirmed',

        ];

        $this->validate($request,$reglas);
        $campos=$request->all();
        $campos['password']=bcrypt($request->password);
        $campos['verified']=User::NO_VERIFICADO;
        $campos['verification_token']=User::generarTokenVerificacion();
        $campos['admin']=User::notIsAdmin;


        $user=User::create($campos);
        return $this->showOne($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User  $user)
    {
       return $this->showOne($user);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
    
        $validateData=$request->validate([
            'email'=>'email|unique:users,email,'.$user->id,
            'password'=>'min:6|confirmed',
            'adimin'=>'in'.User::isAdmin . ',' . User::notIsAdmin
        ]);
      
        if($request->has('email') && $request->input('email') != $user->email){
            $user->verified= User::NO_VERIFICADO;
            $user->verification_token=User::generarTokenVerificacion();
       
            $user->email=$request->email;
          
        }
        if($request->has('password')){
            $user->password= bcrypt($request->password);
        }
        if($request->has('name')){
            $user->name=$request->name;
        
        }
        if($request->has('admin')){
            $user->admin=$request->admin;
            if(!User::esVerificado()){

                return $this->errorResponse('unicamente los usuarios verificados pueden cambiar su estado',409);
            }
            $user->admin=$request->admin;
        }
        //verificar si el usuario se le ha modificado alguna propiedad
        if (!$user->isDirty()) {
           
                return $this->errorResponse('  se debe especificar al menos un valor para actualiar',409);
             
        }
        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(Transaction::where('buyer_id',$user->id)->exists() || Product::where('seller_id',$user->id)->exists()){
            return $this->errorResponse(' no se puede eliminar de forma permanente el recurso porque
            hace parte de algun otro', 409);
        }
        $user->delete();
        return $this->showOne($user);
    }
    public function verificarUsuario($token){
        $user = User::where('verification_token',$token)->firstOrFail();

        $user->verified=User::VERIFICADO;
        $user->verification_token=null;
        $user->save();
        return response()->json('LA CUENTA HA SIDO VERIFICADA');

    }

    public function resend(User $user){
        if($user->esVerificado()){
            return $this->errorResponse('el usuario ya esta verificado',409);
        }
        retry(5,function() use($user){
            Mail::to($user->email)->send(new userCreate($user));

        },100);
       
        return response()->json('se ha enviado el correo exitosamente',200);
    }
}
 