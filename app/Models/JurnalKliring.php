<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalKliring extends Model
{
    protected $table = 't_jurnal_kliring';

    protected $primaryKey = 'jurnal_kliring_id';

    public $timestamps = false;
    protected $fillable = [
		'jurnal_kliring_id',
		'jurnal_kliring_no',
		'id_jurnal_bagian',
		'no_warkat',
        'rek_kliring',
		'id_sandi_transaksi',
		'jenis_warkat',
		'id_kliring_bank_lain',
		'id_kliring_bank_asal',		
		'penyelenggara_kliring',		
		'keterangan',		
		'transaksi',		
		'jenis_usaha',		
		'jurnal_kliring_tanggal',		
		'id_kelas',		
		'dt_record',
		'user_record',
		'dt_modified',
		'user_modified'		
    ];

	public function Kelas(){
		return $this->hasOne('App\Models\Kelas');
	}

	public function SandiTransaksi(){
		return $this->hasOne('App\Models\SandiTransaksiKliring');
	}
	
	public function JurnalBagian(){
		return $this->hasOne('App\Models\JurnalBagian');
	}	
	
}

