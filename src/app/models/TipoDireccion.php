<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDireccion extends Model
{
    protected $table = 'tblc_tipo_direccion';
    protected $primaryKey = 'id_tipo_direccion ';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_tipo_direccion', 'descripcion', 'fecha_modificacion', 'fecha_registro', 'activo'];

    
}
