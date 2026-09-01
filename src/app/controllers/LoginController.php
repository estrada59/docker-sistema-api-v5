<?php

namespace App\Controllers;

/**
 * This is the base controller for your Leaf MVC Project.
 * You can initialize packages or define methods here to use
 * them across all your other controllers which extend this one.
 */
class LoginController extends Controller
{
    // You can define methods here that would be used
    // throughout your controller classes
    // public function someMethod() {}

    /**
     *  Es un ejemplo de como realizar las consultas usando 
     *  POSTMAN o php
     */
    public function index()
    {
        
        if(!isset(auth()->user()->token))
        {

            $validatedData = request()->validate([
                'name' => 'text',
                'password' => 'min:8'
            ]);
                
            if ($validatedData) {
                // data is valid
                auth()->config('hidden', ['password', 'id', 'name', 'email']);

                auth()->config('token.lifetime', '1 hour'); // 1 hour'
                // dump(auth()->config(''));
                
                $user = auth()->login($validatedData);
                // dump($user);
                if (!$user) {
                    response()->exit([
                        'status' => 'error',
                        'message' => 'Login failed',
                        'data' => auth()->errors(),
                    ]);
                }else{
                    //Get data generated on user login
                    $data = auth()->data();
                    // print_r($data);

                    //se convierte el objeto a array
                    $array = (array) $data;
                    
                    $authHeader = request()->headers('Authorization');;

                    $token = str_replace('Bearer ', '', $authHeader);
                    
                    if($token === $array['user']['token']){
                        //Consultas personalizadas
                        // response()->redirect('/admin');     
                        response()->json([
                            'status' => 'Success',
                            'message' => 'Sesión Iniciada'
                        ]);

                    }else{
                        // data is invalid 
                        response()->json([
                            'status' => 'fail',
                            'message' => 'token inválido'
                        ]);
                    }     
                }    
            } else {
                // data is invalid 
                response()->json([
                    'status' => 'fail',
                    'message' => 'datos invalidos',
                    'errors' => request()->errors()
                ]);
            }
        }else{
            response()->json([
                'status' => 'Success',
                'message' => 'Sesión Iniciada'
            ]);
        }
    }

    public function logout()
    {
        auth()->logout();

        response()->json([
            'status' => 'Logout',
            'message' => 'Sesión cerrada'
        ]);
        
    }

    public function statusLogin(){
        
    }

  
  

}