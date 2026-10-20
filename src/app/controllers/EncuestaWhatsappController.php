<?php

namespace App\Controllers;

/**
 * This is the base controller for your Leaf MVC Project.
 * You can initialize packages or define methods here to use
 * them across all your other controllers which extend this one.
 */
class EncuestaWhatsappController extends \Leaf\Controller
{
    public function getEncuesta()
    {
        try
        {
        
            $rows = db()
                        ->query('SELECT id_encuesta_whatsapp,
                                        profile_name,
                                        comment_text 
                                    FROM tbl_encuesta_whatsapp
                                    WHERE comment_text != "No escribio comentario"
                                    AND comment_text != " "
                                    ORDER BY id_encuesta_whatsapp DESC 
                                    LIMIT 200;')
                        ->all();
            
            if (!empty($rows)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Encontramos datos',
                    'comentarios' => $rows
                ], 200);
            }

            return response()->json([
                'status' => 'fail',
                'message' => 'No hay encuestas registrados en este periodo',
                'total' => '0'
            ], 404);
           
        } catch (\PDOException $exception) {
            // Guardar error en un log
            $this->logError($exception, "No se pudo obtener el total de encuestas.");

            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error interno al consultar el total de encuestas.',
                'total' => 0
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
