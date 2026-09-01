<?php

namespace App\Controllers;

/**
 * This is the base controller for your Leaf MVC Project.
 * You can initialize packages or define methods here to use
 * them across all your other controllers which extend this one.
 */
class VisitasMedicosController extends Controller
{
    // You can define methods here that would be used
    // throughout your controller classes
    // public function someMethod() {}

    /**
     * Consulta todas las visitas realizadas desde el año 
     */
    public function visitasMedicos()
    {
        $user = auth()->user(); // Obtiene el usuario autenticado
        if(!$user) {
            return response()->exit([
                'message' => 'Unauthorized Error 401',
                'data' => auth()->errors(),
            ], 401);
        }

        auth()->config('hidden', ['password', 'id', 'name', 'email','token']);

        auth()->config('token.lifetime', '1 hour'); // 1 hour'

        $tokenBd = auth()->user()->token;
        
        //Get Object User generated on user login 
        //$data = auth()->data();
        // print_r($data);
        
        $authHeader = request()->headers('Authorization');;

        $tokenHeader = str_replace('Bearer ', '', $authHeader);
        
        if($tokenHeader === $tokenBd){
            //Consultas personalizadas
            $datosVisitasMedicos = db()
                                ->query('SELECT  tbl_visitas_medicos.id_medico_venta as id_medico,

                                    (SELECT tblc_grado_medico.descripcion
                                        FROM tblc_grado_medico
                                        WHERE tblc_grado_medico.id_grado_medico = (SELECT tbl_medicos_ventas.id_grado_medico
                                                                                    FROM tbl_medicos_ventas
                                                                                    WHERE tbl_medicos_ventas.id_medico_venta = tbl_visitas_medicos.id_medico_venta ) ) AS grado_medico,
                                    
                                    (SELECT concat( tbl_medicos_ventas.nombre," ", tbl_medicos_ventas.apellido_paterno," ",tbl_medicos_ventas.apellido_materno)
                                            FROM tbl_medicos_ventas
                                            WHERE tbl_medicos_ventas.id_medico_venta = tbl_visitas_medicos.id_medico_venta )as nombre_completo,
                                    
                                    (SELECT tblc_especialidad.descripcion 
                                        FROM tblc_especialidad 
                                        WHERE tblc_especialidad.id_especialidad = (SELECT tbl_medico_especialidad_ventas.id_especialidad 
                                                                                        FROM tbl_medico_especialidad_ventas 
                                                                                        WHERE tbl_medico_especialidad_ventas.id_medico_venta = tbl_visitas_medicos.id_medico_venta)) as especialidad,
                                    tbl_visitas_medicos.id_atendio_visitas,
                                    tbl_visitas_medicos.fecha_visita,
                                    tbl_visitas_medicos.latitud  as latitud_visita,
                                    tbl_visitas_medicos.longitud as longitud_visita,
                                    tbl_visitas_medicos.presicion ,
                                    tbl_visitas_medicos.altitud,
                                    tbl_visitas_medicos.observaciones,
                                    
                                    tbl_direccion_medicos.id_medico_venta,
                                    
                                    (SELECT concat(tbl_direccion_medicos.calle," NO. ",
                                            tbl_direccion_medicos.numero_exterior, " INT. ",
                                            tbl_direccion_medicos.numero_interior," COLONIA ",
                                            (SELECT tblc_asentamientos.nom_asen FROM tblc_asentamientos WHERE tblc_asentamientos.id_asentamiento = tbl_direccion_medicos.id_asentamiento),", ",
                                            (SELECT tblc_municipios.nom_mun FROM tblc_municipios WHERE tblc_municipios.id_municipios = tbl_direccion_medicos.id_municipios),", ",
                                            (SELECT tblc_estados.nom_edo FROM tblc_estados WHERE tblc_estados.id_estados = tbl_direccion_medicos.id_estados),"."
                                            ) as direccion
                                        FROM tbl_direccion_medicos
                                        WHERE tbl_direccion_medicos.id_medico_venta = tbl_visitas_medicos.id_medico_venta) as direccion,

                                    tbl_direccion_medicos.referencia,
                                    tbl_direccion_medicos.latitud as latitud_registro,
                                    tbl_direccion_medicos.longitud as longitud_registro,
                                    tbl_direccion_medicos.presicion,
                                    tbl_direccion_medicos.altitud,
                                    tbl_direccion_medicos.fecha_registro
                                            
                                    FROM tbl_visitas_medicos

                                    inner join tbl_direccion_medicos on tbl_direccion_medicos.id_medico_venta = tbl_visitas_medicos.id_medico_venta
                                    WHERE tbl_visitas_medicos.activo = ? order by tbl_visitas_medicos.id_medico_venta')
                                ->bind('1')
                                ->all();

            response()->json($datosVisitasMedicos);    
            // response()->json($allHeaders);
        }else{
            // data is invalid 
            response()->json([
                'status' => 'fail',
                'message' => 'token inválido'
            ]);
        }   
           
    }

    /**
     *  Es un ejemplo de como realizar las consultas usando 
     *  POSTMAN o php
     */
    public function visitasMedicosTest()
    {
        
    }

    
}
