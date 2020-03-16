<?php

namespace App;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes;
    protected $dates=['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const VERIFICADO='1';
    const NO_VERIFICADO='0';
    protected $table='users';
    const isAdmin='true';
    const notIsAdmin='false';

    protected $fillable = [
        'name', 'email', 'password',
        'verified',// propiedad para saber si un usuario esta verificado o no
        'verification_token',//codigo de verificacion que se envia por correo
        'admin',
    ];
    //los mutadores trabajan antes de la insersion a la base de datos
    public function setNameAttribute($value){
        $this->attributes['name']= strtolower($value); //cambia los atributos a minuscula para guardar en la DB
    }
    public function getNameAttribute($value){
        return ucwords($value);//retorna el valor de el nombre convertido en string
    }

    public function setEmailAttribute($value){
            $this->attributes['email']= strtolower($value); //cambia los atributos a minuscula para guardar en la DB
    }
        
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //ocultar  propiedades en las respuestas json
    protected $hidden = [
        'password', 'remember_token', //recuerda al usuario en el front end
        //'verification_token' //unicamente se debe ver desde el correo
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function esVerificado(){
        return $this->verified==self::VERIFICADO;
    }

    public function Admin(){
        return $this->admin==self::isAdmin;
    }

    public static function generarTokenVerificacion(){
       
        return Str::random(40);
    }


}
