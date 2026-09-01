<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    protected $table = 'tblc_estados';
    protected $primaryKey = 'id_estados  ';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_estados', 'cve_ent', 'nom_edo',
                            'nom_abr', 'fecha_modificacion',
                            'fecha_registro', 'activo'];

    
}
