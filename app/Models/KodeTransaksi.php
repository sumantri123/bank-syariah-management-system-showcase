<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeTransaksi extends Model
{
    protected $table = 'm_kode_transaksi';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        'id',
		'kode_transaksi',
		'nama_transaksi',
        'jenis_transaksi',
        'created_at',		
        'updated_at'        
    ];	   

    public function JurnalBagianDet()
    {
        return $this->belongsTo('App\Models\JurnalBagianDetail');
    }
}
