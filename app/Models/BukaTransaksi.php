<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukaTransaksi extends Model
{
    protected $table = 't_buka_transaksi';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        'id',
		'id_kelas',
		'buka_tanggal',
		'buka_aktif',
		'token',
		'batas_token',
		'dt_record',
		'user_record',
		'dt_modified',
		'user_modified'		
    ];

	public function Kelas(){
		return $this->hasOne('App\Models\Kelas');
	}
	
	
	
}

