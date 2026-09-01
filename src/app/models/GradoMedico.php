<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradoMedico extends Model
{
    protected $table = 'tblc_grado_medico';
    protected $primaryKey = 'id_grado_medico';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_grado_medico', 'descripcion',
                            'fecha_registro', 'fecha_modificacion', 'activo'];

    
}
