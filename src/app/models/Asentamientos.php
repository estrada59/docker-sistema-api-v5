<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asentamientos extends Model
{
    protected $table = 'tblc_asentamientos';
    protected $primaryKey = 'id_asentamiento ';
    public $timestamps = false;
    // const CREATED_AT = 'fecha_registro';
    // const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_asentamiento', 'id_estados', 'id_municipios', 'id_tipo_asentamiento', 'codigo_postal', 'nom_asen', 'tipo_asentamiento', 'activo'];

    //Relacion tblc_estados
    public function estados()
    {
        return $this->belongsTo(Estados::class, 'id_estados', 'id_estados');
    }

    //Relacion tblc_municipios
    public function municipios()
    {
        return $this->belongsTo(Municipios::class, 'id_municipios', 'id_municipios');
    }

    //Relacion tblc_tipo_asentamiento
    public function tipoAsentamiento()
    {
        return $this->belongsTo(TipoAsentamiento::class, 'id_tipo_asentamiento', 'id_tipo_asentamiento');
    }
    
}
