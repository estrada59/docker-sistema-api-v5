<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = 'tbl_usuarios';
    protected $primaryKey = 'id_usuario  ';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_usuario', 'id_empleado', 'id_jerarquia ',
                            'nombre_usuario', 'contrasena','fecha_acceso',
                            'fecha_salida', 'sesion_activa', 'fecha_modificacion',
                            'fecha_registro', 'activo'];

    //Relacion tblc_estados
    public function empleados()
    {
        return $this->belongsTo(Empleados::class, 'id_empleado', 'id_empleado');
    }

    //Relacion tblc_municipios
    public function jerarquias()
    {
        return $this->belongsTo(Jerarquias::class, 'id_jerarquia', 'id_jerarquia');
    }

    //Relacion tblc_tipo_asentamiento
    public function tipoAsentamiento()
    {
        return $this->belongsTo(TipoAsentamiento::class, 'id_tipo_asentamiento', 'id_tipo_asentamiento');
    }

    
}
