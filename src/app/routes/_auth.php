<?php

auth()->middleware('auth.required', function () {
    response()->exit([
        'message' => 'Unauthorized Error 401',
        'data' => auth()->errors(),
    ], 401);
});

app()->group('/auth', function () {
    app()->post('/login', ['middleware' => '', 'LoginController@index']);
    app()->get('/logout', 'LoginController@logout');
    // app()->post('/login', 'Auth\LoginController@store');
    // app()->post('/register', 'Auth\RegisterController@store');
    // Reset and recover account will be added later
});

// app()->post('/auth/logout', [
//     'middleware' => 'auth.required',
//     'Auth\LoginController@logout'
// ]);

app()->group('/admin', [
    'middleware' => 'auth.required',
    function () {
        // app()->get('/', 'Auth\AccountController@index');
        app()->post('/visitasMedicos', 'VisitasMedicosController@visitasMedicos');
        app()->post('/agendadoMedicinaNuclear/{fecha}', 'AgendaController@agendaMedicinaNuclear');
        app()->post('/agendadoTomografia/{fecha}', 'AgendaController@agendaTomografia');
        app()->post('/totalPacientesAtendidos', 'AgendaController@totalPacientesAtendidos');
        app()->post('/cobrosParticularMN/{fecha}', 'PagosController@getCobrosParticularMN');
        app()->post('/cobrosPublicaMN/{fecha}', 'PagosController@getCobrosPublicaMN');
        app()->post('/cobrosParticularTomo/{fecha}', 'PagosController@getCobrosParticularTomo');
        app()->post('/cobrosPublicaTomo/{fecha}', 'PagosController@getCobrosPublicaTomo');
    },
]);
