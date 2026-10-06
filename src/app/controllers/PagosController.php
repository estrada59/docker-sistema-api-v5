<?php

namespace App\Controllers;

use App\Controllers\HelpController;

/**
 * This is the base controller for your Leaf MVC Project.
 * You can initialize packages or define methods here to use
 * them across all your other controllers which extend this one.
 * 
 * @author Enrique Estrada <estrada59@gmail.com>
 */
class PagosController extends Controller
{
    /**
     * Obtenemos todos los cobros realizados apartir de la $fecha_estudios
     * y devueve un JSON con todos los pagos Particulares de MN
     */
    public function getCobrosParticularMN($fecha_estudios)
    {     
        $help = new HelpController();
        $fecha_fin = $help->last_year_day($fecha_estudios);
        $fecha_ini = $help->first_year_day($fecha_estudios); 

        $id_depto =1; //Para medicina nuclear

        $this->getCobrosParticular($fecha_ini, $fecha_fin, $id_depto);
    }

    /**
     * Obtenemos todos los cobros realizados apartir de la $fecha_estudios
     * y devueve un JSON con todos los pagos hecho por instituciones Publicas de MN
     */
    public function getCobrosPublicaMN($fecha_estudios)
    {
        $help = new HelpController();
        $fecha_fin = $help->last_year_day($fecha_estudios);
        $fecha_ini = $help->first_year_day($fecha_estudios); 

        $id_depto = 1; //Para medicina nuclear

        $this->getCobrosPublica($fecha_ini, $fecha_fin, $id_depto);      
    }

    /**
     * Obtenemos todos los cobros realizados apartir de la $fecha_estudios
     * y devueve un JSON con todos los pagos Particulares de Tomografia
     */
    public function getCobrosParticularTomo($fecha_estudios)
    {
        
        $help = new HelpController();
        $fecha_fin = $help->last_year_day($fecha_estudios);
        $fecha_ini = $help->first_year_day($fecha_estudios); 

        $id_depto = 2; //Para tomografía

        $this->getCobrosParticular($fecha_ini, $fecha_fin, $id_depto);
            
    }

    /**
     * Obtenemos todos los cobros realizados apartir de la $fecha_estudios
     * y devueve un JSON con todos los pagos hecho por instituciones Publicas de Tomografia
     */
    public function getCobrosPublicaTomo($fecha_estudios)
    {
        $help = new HelpController();
        $fecha_fin = $help->last_year_day($fecha_estudios);
        $fecha_ini = $help->first_year_day($fecha_estudios); 

        $id_depto = 2; //Para tomografía

        $this->getCobrosPublica($fecha_ini, $fecha_fin, $id_depto);   
    }

    /**
     * Consultas SQL para obtener los pagos hechos por los clientes de 
     * manera particular establecemos el rango de búsqueda por año.
     * 
     * Recibe tres parámetros Fecha de inicio de consulta, Fecha final de consulta y 
     * el Id del departamento a consultar
     */
    public function getCobrosParticular($fecha_ini, $fecha_fin, $id_depto)
    {
        try
        {
            $listaCobros = db()
                        ->query("SELECT 
                            p.id_pagos,
                            
                            (select d.id_departamento
                                from tblc_departamentos d
                                where d.id_departamento = (SELECT tbl_lista_precios.id_departamento 
                                                            FROM tbl_lista_precios 
                                                            WHERE tbl_lista_precios.id_lista_precio = tbl_agenda.id_lista_precio ) ) as id_departamento,
                            (select d.descripcion
                                from tblc_departamentos d
                                where d.id_departamento = (SELECT tbl_lista_precios.id_departamento 
                                                            FROM tbl_lista_precios 
                                                            WHERE tbl_lista_precios.id_lista_precio = tbl_agenda.id_lista_precio ) ) as descripcion_departamento,
                            
                            (DATE_FORMAT(p.fecha_pago, '%d/%m/%Y') ) AS fecha_pago,
                                                            
                            concat(tbl_clientes.nombre,' ',tbl_clientes.apellido_paterno,' ',tbl_clientes.apellido_materno) as nombre_completo,
                            
                            tbl_detalle_venta.descripcion as estudio,

                            (select tbl_lista_precios.precio from tbl_lista_precios where tbl_lista_precios.id_lista_precio = tbl_agenda.id_lista_precio) as precio_lista,

                            tbl_ventas.monto_total_pagar,

                            p.monto_restante as monto_restante_pagar_recibo,
                            
                            (SELECT fp.descripcion 
                                    FROM tblc_forma_pagos fp 
                                    WHERE fp.id_forma_pago = p.id_forma_pago  ) as forma_pago,
                                    
                            p.monto_pagar,
                            
                            p.folio,

                            (SELECT ef.descripcion
                                FROM tblc_estatus_facturas ef
                                WHERE ef.id_estatus_factura = (SELECT  f.id_estatus_factura
                                                                    FROM tbl_facturas f
                                                                    WHERE f.id_ventas = p.id_ventas) ) as estatus_factura,
                            
                            (SELECT  f.no_facturas
                                FROM tbl_facturas f
                                WHERE f.id_ventas = p.id_ventas)  as no_facturas,
                            
                            (SELECT concat(tbl_empleados.nombre,' ',tbl_empleados.apellido_paterno,' ', tbl_empleados.apellido_materno) as atendio
                                FROM tbl_empleados
                                WHERE tbl_empleados.id_empleado = (SELECT tbl_usuarios.id_empleado FROM tbl_usuarios WHERE tbl_usuarios.id_usuario = tbl_agenda.id_usuario) ) as agendo,
                            
                            tbl_agenda.notas as notas_agenda,

                            (SELECT concat(tbl_empleados.nombre,' ',tbl_empleados.apellido_paterno,' ', tbl_empleados.apellido_materno) as cobro
                                FROM tbl_empleados
                                WHERE tbl_empleados.id_empleado = (select tbl_usuarios.id_empleado from tbl_usuarios where tbl_usuarios.id_usuario = p.id_usuario) ) as cobro,

                            (SELECT tbl_ventas.notas FROM tbl_ventas WHERE tbl_ventas.id_ventas = tbl_agenda.id_ventas) as notas_venta

                            
                            from tbl_pagos p
                            inner join tbl_agenda on tbl_agenda.id_agenda = p.id_agenda
                            INNER JOIN tbl_clientes ON tbl_agenda.id_cliente = tbl_clientes.id_cliente
                            INNER JOIN tbl_detalle_venta on tbl_agenda.id_ventas = tbl_detalle_venta.id_ventas
                            inner join tbl_ventas on p.id_ventas = tbl_ventas.id_ventas
                            INNER JOIN tbl_lista_precios on tbl_agenda.id_lista_precio = tbl_lista_precios.id_lista_precio
                            
                            INNER JOIN tblc_tipo_instituciones on tbl_agenda.id_tipo_institucion = tblc_tipo_instituciones.id_tipo_institucion
                            INNER JOIN tbl_medicos on tbl_agenda.id_medico = tbl_medicos.id_medico
                            INNER JOIN tblc_estatus_agenda on tbl_agenda.id_estatus_agenda = tblc_estatus_agenda.id_estatus_agenda
                            
                            where (p.fecha_pago BETWEEN ? AND ?) and (id_departamento = ?) and ( tblc_tipo_instituciones.id_tipo_institucion = 1 )Order By p.fecha_pago")
                        ->bind($fecha_ini, $fecha_fin, $id_depto)
                        ->all();  

            if (!empty($listaCobros)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Encontramos datos',
                    'data' => $listaCobros
                ], 200);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'No hay datos registrados en este periodo',
                'data' => []
            ], 404);
                       

            
        } catch (\PDOException $exception) {
            // Guardar error en un log
            $this->logError($exception, "No se pudo obtener los cobros particulares.");

            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error interno al consultar los cobros particulares.',
                'data' => []
            ], 500);
        }
    }

    /**
     * Consultas SQL para obtener los pagos hechos por los clientes de 
     * manera publica también conocido como instituciones establecemos el rango de búsqueda por año.
     * 
     * Recibe tres parámetros Fecha de inicio de consulta, Fecha final de consulta y 
     * el Id del departamento a consultar
     */
    public function getCobrosPublica($fecha_ini, $fecha_fin, $id_depto)
    {
        try
        {

            $listaCobros = db()
                        ->query("SELECT 
                            p.id_pagos,
                            
                            (select d.id_departamento
                                from tblc_departamentos d
                                where d.id_departamento = (SELECT tbl_lista_precios.id_departamento 
                                                            FROM tbl_lista_precios 
                                                            WHERE tbl_lista_precios.id_lista_precio = tbl_agenda.id_lista_precio ) ) as id_departamento,
                            (select d.descripcion
                                from tblc_departamentos d
                                where d.id_departamento = (SELECT tbl_lista_precios.id_departamento 
                                                            FROM tbl_lista_precios 
                                                            WHERE tbl_lista_precios.id_lista_precio = tbl_agenda.id_lista_precio ) ) as descripcion_departamento,
                            
                            (DATE_FORMAT(p.fecha_pago, '%d/%m/%Y') ) AS fecha_pago,
                                                            
                            concat(tbl_clientes.nombre,' ',tbl_clientes.apellido_paterno,' ',tbl_clientes.apellido_materno) as nombre_completo,
                            
                            tbl_detalle_venta.descripcion as estudio,

                            (select tbl_lista_precios.precio from tbl_lista_precios where tbl_lista_precios.id_lista_precio = tbl_agenda.id_lista_precio) as precio_lista,

                            tbl_ventas.monto_total_pagar,

                            p.monto_restante as monto_restante_pagar_recibo,
                            
                            (SELECT fp.descripcion 
                                    FROM tblc_forma_pagos fp 
                                    WHERE fp.id_forma_pago = p.id_forma_pago  ) as forma_pago,
                                    
                            p.monto_pagar,
                            
                            p.folio,

                            (SELECT ef.descripcion
                                FROM tblc_estatus_facturas ef
                                WHERE ef.id_estatus_factura = (SELECT  f.id_estatus_factura
                                                                    FROM tbl_facturas f
                                                                    WHERE f.id_ventas = p.id_ventas) ) as estatus_factura,
                            
                            (SELECT  f.no_facturas
                                FROM tbl_facturas f
                                WHERE f.id_ventas = p.id_ventas)  as no_facturas,
                            
                            (SELECT concat(tbl_empleados.nombre,' ',tbl_empleados.apellido_paterno,' ', tbl_empleados.apellido_materno) as atendio
                                FROM tbl_empleados
                                WHERE tbl_empleados.id_empleado = (SELECT tbl_usuarios.id_empleado FROM tbl_usuarios WHERE tbl_usuarios.id_usuario = tbl_agenda.id_usuario) ) as agendo,
                            
                            tbl_agenda.notas as notas_agenda,

                            (SELECT concat(tbl_empleados.nombre,' ',tbl_empleados.apellido_paterno,' ', tbl_empleados.apellido_materno) as cobro
                                FROM tbl_empleados
                                WHERE tbl_empleados.id_empleado = (select tbl_usuarios.id_empleado from tbl_usuarios where tbl_usuarios.id_usuario = p.id_usuario) ) as cobro,

                            (SELECT tbl_ventas.notas FROM tbl_ventas WHERE tbl_ventas.id_ventas = tbl_agenda.id_ventas) as notas_venta

                            
                            from tbl_pagos p
                            inner join tbl_agenda on tbl_agenda.id_agenda = p.id_agenda
                            INNER JOIN tbl_clientes ON tbl_agenda.id_cliente = tbl_clientes.id_cliente
                            INNER JOIN tbl_detalle_venta on tbl_agenda.id_ventas = tbl_detalle_venta.id_ventas
                            inner join tbl_ventas on p.id_ventas = tbl_ventas.id_ventas
                            INNER JOIN tbl_lista_precios on tbl_agenda.id_lista_precio = tbl_lista_precios.id_lista_precio
                            
                            INNER JOIN tblc_tipo_instituciones on tbl_agenda.id_tipo_institucion = tblc_tipo_instituciones.id_tipo_institucion
                            INNER JOIN tbl_medicos on tbl_agenda.id_medico = tbl_medicos.id_medico
                            INNER JOIN tblc_estatus_agenda on tbl_agenda.id_estatus_agenda = tblc_estatus_agenda.id_estatus_agenda
                            
                            where (p.fecha_pago BETWEEN ? AND ?) and (id_departamento = ?) and ( tblc_tipo_instituciones.id_tipo_institucion = 2 )Order By p.fecha_pago")
                        ->bind($fecha_ini, $fecha_fin, $id_depto)
                        ->all();  

            
            if (!empty($listaCobros)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Encontramos datos',
                    'data' => $listaCobros
                ], 200);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'No hay datos registrados en este periodo',
                'data' => []
            ], 404);
            
        } catch (\PDOException $exception) {
                // Guardar error en un log
                $this->logError($exception, "No se pudo obtener los cobros de instituciones.");

                response()->json([
                    'status' => 'fail',
                    'msj' => 'No se pudo obtener los cobros de instituciones.',
                    'clientes' => ''
                ]);
        }
    }

    /**
     * Obtiene los errores en las consultas insert update delete y select
     * y guarga un registro log en caso de que falle
     * 
     * Rev. 2026_10_05
     */
    private function logError(\PDOException $exception, string $message): void
    {
        $error = "Error en línea " . $exception->getLine() . 
                 ": $message. Archivo " . $exception->getFile() . 
                 " Mensaje: " . $exception->getMessage();

        $archivo_registro = 'errorSql.log';
        $marca_tiempo = date('Y-m-d H:i:s');
        $mensaje_registro = "[$marca_tiempo] $error\n";

        file_put_contents($archivo_registro, $mensaje_registro, FILE_APPEND);
    }

}
