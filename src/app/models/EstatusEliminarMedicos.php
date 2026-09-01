<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstatusEliminarMedicos extends Model
{
    protected $table = 'tblc_estatus_eliminar_medicos';
    protected $primaryKey = 'id_estatus_eliminar_medicos  ';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_estatus_eliminar_medicos ', 'descripcion',
                            'fecha_modificacion', 'fecha_registro',
                            'activo'];

    
}