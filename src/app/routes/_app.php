<?php
/**
 * Leaf provides a CORS package which helps you simply
 *  configure and handle CORS in your app. This package
 *  allows you to configure which origins (websites),
 *  headers, ... should be allowed in your app.
 */
app()->cors();

app()->cors([
    'origin' => 'https://medicinanucleardechiapas.com',
    'optionsSuccessStatus' => 200
]);

/**
 * Prevenir solicitudes CSRF
 */
app()->csrf();
app()->csrf([
  'cookie' => false,
]);

// // Display the token in your views or to send the token to your frontend
app()->get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf()->token()]);
});

/**
 * Configuramos la conexión usando .env
 */
db()->autoConnect();

/**
 * Configuramos la conexión usando .env
 */
auth()->autoConnect();
 
auth()->config([
    "db.table" => "tbl_usuarios_api",
    "id.key" => "id",
    "password.key" => "password",
]);

//Para Producción

/**
 * Creación de Middleware
 */
app()->registerMiddleware('statusLogin', function () {
    $user = auth()->user(); // Obtiene el usuario autenticado

    if (!$user) {
        return response()->json([
                    'status' => 'fail',
                    'message' => 'Debes iniciar sesión'
                ]);
    }

    return response()->json([
                'status' => 'Success',
                'message' => 'Sesión Iniciada'
            ]);
});

/**
 * Agupamos las rutas de autenticación
 */
// app()->group('/auth', function () {
//     app()->post('/login', ['middleware' => '', 'LoginController@index']);
//     app()->get('/logout', 'LoginController@logout');
//     // Reset and recover account will be added later
// });


/**
 * Se usa Middleware para validar si el usuario inicio sesion
 * si es verdadero entonces permite consulta
 * 
 * Agrupamos Rutas de usuarios que ya iniciaron sesion
 */
// app()->group('/admin',  function () {
    // app()->post('/visitasMedicos',  'VisitasMedicosController@visitasMedicos');
    // app()->post('/agendadoMedicinaNuclear/{fecha}',  ['middleware' => 'auth.required', 'AgendaController@agendaMedicinaNuclear']);
    // app()->post('/agendadoTomografia/{fecha}', ['middleware' => 'auth.required', 'AgendaController@agendaTomografia']);
    // app()->post('/totalPacientesAtendidos', ['middleware' => 'auth.required', 'AgendaController@totalPacientesAtendidos']);
    // app()->post('/cobrosParticularMN/{fecha}', ['middleware' => 'auth.required', 'PagosController@getCobrosParticularMN']);
    // app()->post('/cobrosPublicaMN/{fecha}', ['middleware' => 'auth.required', 'PagosController@getCobrosPublicaMN']);
    // app()->post('/cobrosParticularTomo/{fecha}', ['middleware' => 'auth.required', 'PagosController@getCobrosParticularTomo']);
    // app()->post('/cobrosPublicaTomo/{fecha}', ['middleware' => 'auth.required', 'PagosController@getCobrosPublicaTomo']);
// });

/**
 *  This route is only accessible to guest users
 */
// app()->get('/', ['middleware' => 'auth.check', function () {
//     //Salida con JSON
//     response()->json(['message' => 'Congrats!! You\'re on Medicina Nuclear Please start a sesion']);
// }]);

app()->get('/', ['middleware' => 'statusLogin', function () {
    //Salida con JSON
    // response()->json(['message' => 'Congrats!! You\'re on Medicina Nuclear Please start a sesion']);  
}]);


/**
 * Only for test local
 */
// app()->get('/test', 'TestController@getVisitasMedicos');
// app()->get('/test', 'TestController@totalPacientesAtendidos');

// app()->get('/generaToken', 'TestController@generateToken');
// app()->get('/generatePassword/{password}', 'TestController@generatePassword');


