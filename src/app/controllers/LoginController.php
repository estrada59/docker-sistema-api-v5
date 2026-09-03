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

    public function index()
    {
        $validatedData = request()->validate([
            'name' => 'text',
            'password' => 'min:8'
        ]);

        if (!$validatedData) {

            return response()->json([
                'status' => 'fail',
                'message' => 'Datos inválidos',
                'errors' => request()->errors()
            ], 422);
        }

        auth()->config('hidden', [
            'password',
            'id',
            'name',
            'email'
        ]);

        auth()->config('token.lifetime', '1 hour');

        $user = auth()->login($validatedData);

        if (!$user) {

            return response()->json([
                'status' => 'error',
                'message' => 'Login failed',
                'data' => auth()->errors(),
            ], 401);
        }

        $data = auth()->data();
      
        return response()->json([
            'status' => 'success',
            'message' => 'Sesión iniciada',
            'token' => $data->user['token']
        ]);
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