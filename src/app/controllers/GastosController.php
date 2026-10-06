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
class GastosController extends Controller
{
    /**
     * Obtenemos todos los gastos realizados apartir de la $fecha_estudios
     * y devolvemos un JSON con todos los gastos de MN
     */
    public function getGastosMNporAño($fecha_gastos)
    {
        $help = new HelpController();
        $fecha_fin = $help->last_year_day($fecha_gastos);
        $fecha_ini = $help->first_year_day($fecha_gastos); 

        $id_depto =1; //Para medicina nuclear

        $this->getDataGastosporAño($fecha_ini, $fecha_fin, $id_depto, 1);      
    }
    /**
     * Obtenemos todos los gastos realizados apartir de la $fecha_estudios
     * y devolvemos un JSON con todos los gastos de Tomografía
     */
    public function getGastosTomografiaPorAño($fecha_gastos)
    {
        $help = new HelpController();
        $fecha_fin = $help->last_year_day($fecha_gastos);
        $fecha_ini = $help->first_year_day($fecha_gastos); 

        $id_depto =2; //Para tomografía

        $this->getDataGastosporAño($fecha_ini, $fecha_fin, $id_depto, 1);      
    }

    /**
     * Consultas SQL para obtener gastos realizados en el año.
     * 
     * Recibe tres parámetros Fecha de inicio de consulta, Fecha final de consulta y 
     * el Id del departamento a consultar
     */
    public function getDataGastosporAño($fecha_ini, $fecha_fin, $id_depto, $activo) 
    {
        try
        {
        
            $rows = [];

            $listaGastos = db()
                    ->query("SELECT    tbl_gastos.id_gastos,
                                tbl_gastos.id_departamento,
                                tblc_departamentos.descripcion,
                                tbl_gastos.id_tipo_gasto,
                                tblc_tipo_gasto.descripcion AS descripcion_tipo_gasto,
                                tbl_gastos.id_forma_pago,
                                tblc_forma_pago_gasto.descripcion AS decripcion_forma_pago,
                                tbl_gastos.id_usuario,
                                DATE_FORMAT(tbl_gastos.fecha_gasto, '%d-%m-%Y') AS fecha_gasto,
                                tbl_gastos.fecha_gasto AS fecha_gasto_sin_formato,
                                tbl_gastos.concepto,
                                tbl_gastos.monto,
                                tbl_gastos.observaciones
                        FROM tbl_gastos
                        INNER JOIN tblc_departamentos 
                            ON tbl_gastos.id_departamento = tblc_departamentos.id_departamento
                        INNER JOIN tblc_forma_pago_gasto 
                            ON tbl_gastos.id_forma_pago = tblc_forma_pago_gasto.id_forma_pago
                        INNER JOIN tblc_tipo_gasto 
                            ON tbl_gastos.id_tipo_gasto = tblc_tipo_gasto.id_tipo_gasto
                        WHERE tbl_gastos.activo = ?
                        AND tbl_gastos.id_departamento = ?
                        AND tbl_gastos.fecha_gasto BETWEEN ? AND ?
                        ORDER BY tbl_gastos.fecha_gasto")
                    ->bind($activo, $id_depto, $fecha_ini, $fecha_fin)
                    ->all(); 

            if (!empty($listaGastos)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Encontramos datos',
                    'data' => $listaGastos
                ], 200);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'No hay datos registrados en este periodo',
                'data' => []
            ], 404);
            
        } catch (\PDOException $exception) {
                // Guardar error en un log
                $this->logError($exception, "No se pudo obtener los gastos.");

                return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error interno al consultar los gastos.',
                'data' => []
            ], 500);
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
