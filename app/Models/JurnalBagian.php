<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalBagian extends Model
{
    protected $table = 't_jurnal_bagian';

    protected $primaryKey = 'jurnal_bagian_id';

    public $timestamps = false;
    protected $fillable = [
        'jurnal_bagian_id',
		'jurnal_no',
		'jurnal_keterangan',
		'jurnal_tanggal',
		'jurnal_bagian',
		'kode_transaksi',
		'id_nilai_tukar',		
		'id_kelas',		
		'dt_record',
		'user_record',
		'flag',
		'dt_modified',
		'user_modified'		
    ];

	public function Kelas(){
		return $this->hasOne('App\Models\Kelas');
	}
	
	public function JurnalBagianDet()
    {
        return $this->belongsTo('App\Models\JurnalBagianDetail');
    }

	public function JurnalRTGS()
    {
        return $this->belongsTo('App\Models\JurnalRTGS');
    }
	
}

