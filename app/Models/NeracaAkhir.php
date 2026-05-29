<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NeracaAkhir extends Model
{
    protected $table = 't_neraca_akhir';

    protected $primaryKey = 'neraca_akhir_id';

    public $timestamps = false;
    protected $fillable = [
        'neraca_akhir_id',
		'tanggal',
		'bulan',
		'tahun',
		'tanggal_yt',
		'id_kelas',
		'created_at',		
		'updated_at'		
    ];	
	
}

