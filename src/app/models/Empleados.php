<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    protected $table = 'tbl_empleados';
    protected $primaryKey = 'id_empleado ';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_jerarquia', 'descripcion', 'fecha_modificacion', 'fecha_registro', 'activo'];
    
}
