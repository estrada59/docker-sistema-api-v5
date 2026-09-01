<?php

namespace App\Models;


class VisitasMedicos extends Model
{
    protected $table = 'tbl_visitas_medicos';
    protected $primaryKey = 'id_visitas_medicos';
    // // public $timestamps = false; // Desactiva timestamps si no usas created_at y updated_at
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = [
        'id_usuario', 'id_medico_venta', 'id_atendio_visitas', 'fecha_visita',
        'latitud', 'longitud', 'presicion', 'altitud', 'observaciones', 'activo'
    ];

    // Relación con tbl_medicos_ventas
    public function medicoVentas()
    {
        return $this->belongsTo(MedicosVentas::class, 'id_medico_venta', 'id_medico_venta');
    }

    // Relación con tbl_direccion_medicos
    public function direccionMedicos()
    {
        return $this->hasOne(DireccionMedicos::class, 'id_medico_venta', 'id_medico_venta');
    }
}

