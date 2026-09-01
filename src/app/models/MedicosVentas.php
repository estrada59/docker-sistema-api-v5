<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicosVentas extends Model
{
    protected $table = 'tbl_medicos_ventas';
    protected $primaryKey = 'id_medico_venta';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_grado_medico', 'id_sexo', 'id_usuario',
                            'nombre', 'apellido_paterno', 'apellido_materno',
                            'fecha_modificacion', 'fecha_registro', 'activo',
                            'id_estatus_eliminar_medicos'];

    // Relación con tblc_grado_medico
    public function gradoMedico()
    {
        return $this->belongsTo(GradoMedico::class, 'id_grado_medico', 'id_grado_medico');
    }

    // Relación con tbl_medico_especialidad_ventas
    public function especialidad()
    {
        return $this->hasOne(MedicoEspecialidad::class, 'id_medico_venta', 'id_medico_venta');
    }
}
