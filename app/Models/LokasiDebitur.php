<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiDebitur extends Model
{
    protected $table = 'm_lokasi_debitur';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        'id',
		'kode',
		'nama',
		'created_at',
		'updated_at',
		'id_lembaga',		
    ];

	public function RekeningPinjaman()
    {
        return $this->belongsTo('App\Models\TRekeningPinjaman');
    }
	
}

