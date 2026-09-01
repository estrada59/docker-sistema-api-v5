<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtendioVisitas extends Model
{
    protected $table = 'tbl_atendio_visitas';
    protected $primaryKey = 'id_atendio_visitas  ';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = [ 'id_atendio_visitas ', 'nombre', 'apellido_paterno', 
                            'apellido_materno', 'cargo', 'fecha_modificacion', 'fecha_registro', 'activo'];

    
}
