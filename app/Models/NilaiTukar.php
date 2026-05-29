<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiTukar extends Model
{
    protected $table = 'm_nilai_tukar';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        'id',
		'kurs_nama',
		'kurs_beli',
        'kurs_jual',		
	'id_kelas',		
		'created_at',
		'updated_at'
    ];
	
}
