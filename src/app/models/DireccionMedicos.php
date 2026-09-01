<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionMedicos extends Model
{
    protected $table = 'tbl_direccion_medicos';
    protected $primaryKey = '	id_direccion_medicos';
    // public $timestamps = false;
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['id_medico_venta', 'id_estados', 'id_municipios',
                            'id_asentamiento', 'id_tipo_direccion', 'calle',
                            'numero_exterior', 'numero_interior', 'referencia',
                            'latitud', 'longitud', 'presicion', 'altitud',
                            'fecha_modificacion', 'fecha_registro', 'activo'];

    //Relación tbl_medicos_ventas
    public function medicosVentas()
    {
        return $this->belongsTo(MedicosVentas::class, 'id_medico_venta', 'id_medico_venta');
    }

    //Relacion tblc_asentamientos
    public function asentamientos()
    {
        return $this->belongsTo(Asentamientos::class, 'id_asentamiento', 'id_asentamiento');
    }

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

    //Relacion tblc_tipo_direccion
    public function tipoDireccion()
    {
        return $this->belongsTo(TipoDireccion::class, 'id_tipo_direccion', 'id_tipo_direccion');
    }
}
