<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use App\Traits\ApiResponser;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];
use ApiResponser;
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {

        if ($exception  instanceof ValidationException) {
            # code...
            return $this->convertValidationExceptionToResponse($exception,$request);
        }

        //agregar excepcion de que el modelo que intenta instanciar no fue encontrado
        if($exception instanceof ModelNotFoundException){
            $model=strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("no existe el modelo de {$model} con el id especificado",404);
      
        }
    //si existe una instacia de un metodo no authenticaded entonces retornara la falla
        if ($exception instanceof AuthenticationException) {//queda pendiente algo
            return $this->unauthenticated($request, $exception)
            ;}
            //excepcion de autorizacion no tiene acceso a esa pagina le dirá
        if ($exception instanceof AuthorizationException) {
                return $this->errorResponse('no posee permisos para acceder a esta ruta',403);
          
        }   
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('no se encontro la ruta indicada',404);
        
    }   
    if ($exception instanceof MethodNotAllowedHttpException) {
        return $this->errorResponse('el metodo especificado en la peticion no es valido',405);
      
    }  
    if ($exception instanceof HttpException) {
        return response()->json(['error'=>$exception->getMessage(),
        'code'=>$exception->getStatusCode()
    ],$exception->getStatusCode())
    ;}  

        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors=$e->validator->errors()->getMessages();
        return $this->errorResponse($errors,422);
    }
}
