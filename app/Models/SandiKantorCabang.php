<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SandiKantorCabang extends Model
{
    protected $table = 'm_sandi_kantor_cabang';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        "id",
        "kode_kantor",
        "sandi_pengirim",
        "sandi_penerima",		
        "created_at",
        "updated_at",
		"id_lembaga",
    ];

    // public function RekeningNasabah()
    // {
    //     return $this->belongsTo('App\Models\TRekeningNasabah');
    // }
}

