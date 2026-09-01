<?php

namespace App\Controllers;
use App\Controllers\HelpController;

/**
 * This is the base controller for your Leaf MVC Project.
 * You can initialize packages or define methods here to use
 * them across all your other controllers which extend this one.
 */
class AgendaController extends Controller 
{
    // You can define methods here that would be used
    // throughout your controller classes
    // public function someMethod() {}

    /**
     * Obtiene todos los pacientes agendados de Medicina Nuclear
     */
    public function agendaMedicinaNuclear($fecha_estudios)
    {
        $user = auth()->user(); // Obtiene el usuario autenticado

        if($user)
        {
            auth()->config('hidden', ['password', 'id', 'name', 'email','token']);

            auth()->config('token.lifetime', '1 hour'); // 1 hour'

            $tokenBd = auth()->user()->token;
            
            $authHeader = request()->headers('Authorization');;

            $tokenHeader = str_replace('Bearer ', '', $authHeader);
            
            if($tokenHeader === $tokenBd){
            

                $help = new HelpController();
                $fecha_fin = $help->last_year_day($fecha_estudios);
                $fecha_ini = $help->first_year_day($fecha_estudios); 

                $arrayDeptos =1; //Para medicina nuclear

                $this->getByMonth($fecha_ini, $fecha_fin, $arrayDeptos);
        
            }else{
                // data is invalid 
                response()->json([
                    'status' => 'fail',
                    'message' => 'token inválido'
                ]);
            }
        } else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Debes iniciar sesión'
            ]);
        }   
    }

    /**
     * Obtiene todos los pacientes agendados de Tomografía
     */
    public function agendaTomografia($fecha)
    {
        $user = auth()->user(); // Obtiene el usuario autenticado

        if($user)
        {
            auth()->config('hidden', ['password', 'id', 'name', 'email','token']);

            auth()->config('token.lifetime', '1 hour'); // 1 hour'

            $tokenBd = auth()->user()->token;
            
            $authHeader = request()->headers('Authorization');;

            $tokenHeader = str_replace('Bearer ', '', $authHeader);
            
            if($tokenHeader === $tokenBd){
                $fecha_estudios = $fecha;

                $help = new HelpController();
                $fecha_fin = $help->last_year_day($fecha_estudios);
                $fecha_ini = $help->first_year_day($fecha_estudios);

                $arrayDeptos =2; //Para tomografía

                $this->getByMonth($fecha_ini, $fecha_fin, $arrayDeptos);
        
            }else{
                // data is invalid 
                response()->json([
                    'status' => 'fail',
                    'message' => 'token inválido'
                ]);
            }
        } else{
            return response()->json([
                'status' => 'fail',
                'message' => 'Debes iniciar sesión'
            ]);
        }   
    }

    /**
     * Obtiene todos los pacientes agendados en el mes seleccionado
     * de acuerdo con el departamento (MEDICINA NUCLEAR)
     */
    public function getByMonth($fecha_ini, $fecha_fin, $ids_departamentos) :void
    {
        $fecha_ini = $fecha_ini.' 00:00:00';
        $fecha_fin = $fecha_fin.' 23:59:59';
        // response()->json($fecha_fin);
        
        $rows = db()
                    ->query('SELECT count(tbl_agenda.id_agenda) as total, count(tbl_agenda.id_agenda) as total2 FROM tbl_agenda WHERE tbl_agenda.activo = ?')
                    ->bind('1')->fetchObj();
                    
        // $total = (int) $rows[0]['total']; 

        if ($rows->total> 0) {
            
            $id_depto = $ids_departamentos;

            $datosPacientesAgendados = db()
                            ->query("SELECT 
                                            tbl_agenda.id_agenda,
                                            
                                            tbl_agenda.id_cliente,
                                            
                                            (DATE_FORMAT(tbl_agenda.fecha_cita, '%d-%m-%Y') ) AS fecha_cita,
                                            
                                            concat(tbl_clientes.nombre,' ',tbl_clientes.apellido_paterno,' ',tbl_clientes.apellido_materno) as nombre_completo,
                                            
                                            tbl_detalle_venta.descripcion as estudio,
                                        
                                            (DATE_FORMAT(tbl_agenda.fecha_cita, '%h:%i %p') ) AS hora,
                                            
                                            tblc_tipo_instituciones.descripcion as tipo_institucion,
                                            
                                            concat((SELECT tblc_grado_medico.descripcion 
                                                                FROM tblc_grado_medico 
                                                                WHERE tblc_grado_medico.id_grado_medico = tbl_medicos.id_grado_medico ),' ',tbl_medicos.nombre,' ',tbl_medicos.apellido_paterno,' ',tbl_medicos.apellido_materno) as medico,
                                                
                                            (SELECT group_concat(tbl_telefono_clientes.telefono SEPARATOR ', ') as telefonos
                                                            FROM tbl_telefono_clientes
                                                            WHERE tbl_telefono_clientes.id_cliente = tbl_agenda.id_cliente) AS telefono_cliente,
                                            
                                        
                                            tblc_estatus_agenda.id_estatus_agenda as id_estatus,
                                            tblc_estatus_agenda.descripcion as estatus,
                                            
                                            
                                            
                                            (SELECT tblc_colores.descripcion
                                                FROM tblc_colores
                                                WHERE tblc_colores.id_color = (SELECT tblc_estatus_agenda.id_color
                                                                                FROM tblc_estatus_agenda
                                                                                WHERE tblc_estatus_agenda.id_estatus_agenda = tbl_agenda.id_estatus_agenda ) ) as color_descripcion,
                                            
                                            tbl_clientes.nombre,
                                            tbl_clientes.apellido_paterno,
                                            tbl_clientes.apellido_materno,
                                            tbl_clientes.peso,
                                            tbl_clientes.fecha_nacimiento,
                                            tbl_clientes.email,
                                            tbl_clientes.id_sexo,
                                            
                                            (SELECT tblc_sexos.descripcion
                                                FROM tblc_sexos
                                                WHERE tblc_sexos.id_sexo = tbl_clientes.id_sexo) as sexo_descripcion,
                                            
                                            tbl_clientes.id_edad,
                                            tbl_clientes.edad,
                                            (SELECT tblc_edad.descripcion
                                                FROM tblc_edad
                                                WHERE tblc_edad.id_edad = tbl_clientes.id_edad) as edad_descripcion,
                                                
                                            (SELECT concat(tbl_empleados.nombre,' ',tbl_empleados.apellido_paterno,' ', tbl_empleados.apellido_materno) as atendio
                                                FROM tbl_empleados
                                                WHERE tbl_empleados.id_empleado = (SELECT tbl_usuarios.id_empleado FROM tbl_usuarios WHERE tbl_usuarios.id_usuario = tbl_agenda.id_usuario) ) as agendo,
                                            
                                            tbl_agenda.notas,
                                            tbl_agenda.id_lista_precio, 
                                            tbl_lista_precios.id_institucion,
                                            
                                            
                                            (SELECT tblc_instituciones.descripcion 
                                                FROM tblc_instituciones 
                                                WHERE tblc_instituciones.id_institucion = tbl_lista_precios.id_institucion) as institucion_descripcion,

                                            (SELECT tbl_ventas.monto_restante_pagar FROM tbl_ventas WHERE tbl_ventas.id_ventas = tbl_agenda.id_ventas) as debe,

                                            (select d.id_departamento
                                                from tblc_departamentos d 
                                                where d.id_departamento = (select lp.id_departamento 
                                                                            from tbl_lista_precios lp 
                                                                            where lp.id_lista_precio = tbl_agenda.id_lista_precio ) ) as id_departamento,

                                            (select d.descripcion
                                                from tblc_departamentos d 
                                                where d.id_departamento = (select lp.id_departamento 
                                                                            from tbl_lista_precios lp 
                                                                            where lp.id_lista_precio = tbl_agenda.id_lista_precio ) ) as departamento,
                                            tbl_agenda.dosis_tratamiento
                                            
                                            
                                        FROM tbl_agenda
                                    INNER JOIN tbl_clientes ON tbl_agenda.id_cliente = tbl_clientes.id_cliente
                                    INNER JOIN tbl_lista_precios on tbl_agenda.id_lista_precio = tbl_lista_precios.id_lista_precio
                                    INNER JOIN tbl_detalle_venta on tbl_agenda.id_ventas = tbl_detalle_venta.id_ventas
                                    INNER JOIN tblc_tipo_instituciones on tbl_agenda.id_tipo_institucion = tblc_tipo_instituciones.id_tipo_institucion
                                    INNER JOIN tbl_medicos on tbl_agenda.id_medico = tbl_medicos.id_medico
                                    INNER JOIN tblc_estatus_agenda on tbl_agenda.id_estatus_agenda = tblc_estatus_agenda.id_estatus_agenda
                                        
                                    WHERE (tbl_agenda.fecha_cita between ? and ?) and id_departamento = ? AND tbl_agenda.activo = 1 ORDER BY tbl_agenda.fecha_cita;")
                            ->bind($fecha_ini, $fecha_fin, $id_depto)
                            ->all();
            

            if( $datosPacientesAgendados != null ){

                response()->json([
                    'status' => 'Success',
                    'msj' => 'Encontramos datos',
                    'clientes' => $datosPacientesAgendados
                ]);

            }else{

                response()->json([
                    'status' => 'fail',
                    'msj' => 'No hay datos...',
                    'clientes' => ''
                ]);

            }
        }
        else{
            // data is invalid 
            response()->json([
                'status' => 'fail',
                'msj' => 'No hay datos...',
                'clientes' => ''
            ]);
        }
   
    }

    /**
     * Total de pacientes atendidos desde el inicio de operaciones hasta la fecha actual
     */
    public function totalPacientesAtendidos()
    {
        
        
            $rows = db()
                        ->query('SELECT count(tbl_agenda.id_agenda) as total  FROM tbl_agenda WHERE tbl_agenda.id_estatus_agenda = ?')
                        ->bind('2')->fetchObj();
            
            $totalAgenda = (int) $rows->total;

            $pacSistemaViejo = 8788 ; //contabilizado desde 2015-2023 Query usado: "SELECT count(idpacientes) as total from pacientes where pacientes.fecha BETWEEN '2015-01-01' and '2023-12-31' and estatus = 2;"
            $pacSistemaViejoTransision = 560; // contabilizando pacientes enero-junio 2024  #SELECT count(idpacientes) as total from pacientes where pacientes.fecha BETWEEN '2024-01-01' and '2024-06-31' and estatus = 2;#
            $total = $pacSistemaViejo + $totalAgenda +$pacSistemaViejoTransision;
            

            if ($totalAgenda> 0) {
                response()->json([
                    'status' => 'Success',
                    'msj' => 'Encontramos datos',
                    'total' => $total
                ]);
            }
            else{
                // data is invalid 
                response()->json([
                    'status' => 'fail',
                    'msj' => 'No hay datos...',
                    'total' => '0'
                ]);
            }
    
       
         
    }
    
}
