<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAsentamiento extends Model
{
    protected $table = 'tblc_tipo_asentamiento';
    protected $primaryKey = 'id_tipo_asentamiento  ';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_tipo_asentamiento ', 'cve_tipo_asen', 'tipo_asen', 'fecha_modificacion', 'fecha_registro', 'activo'];

   
}
