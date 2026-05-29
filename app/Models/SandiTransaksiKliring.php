<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SandiTransaksiKliring extends Model
{
    protected $table = 'm_sandi_transaksi';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        "id",
        "kode_transaksi_kliring",
        "nama_transaksi_kliring",
        "id_jenis_transaksi",        
        "created_at",
        "updated_at"
    ];

    // public function RekeningNasabah()
    // {
    //     return $this->belongsTo('App\Models\TRekeningNasabah');
    // }
}

