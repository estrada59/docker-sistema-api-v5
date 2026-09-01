<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipios extends Model
{
    protected $table = 'tblc_municipios';
    protected $primaryKey = 'id_municipios';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_municipios', 'id_estados ', 'cve_mun', 'nom_mun', 'fecha_modificacion', 'fecha_registro', 'activo'];

    
}
