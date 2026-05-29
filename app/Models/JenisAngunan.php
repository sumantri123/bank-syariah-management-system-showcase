<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisAngunan extends Model
{
    protected $table = 'm_jenis_angunan';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        'id',
		'kode',
		'nama',
		'created_at',
		'updated_at'			
    ];

	public function RekeningPinjaman()
    {
        return $this->belongsTo('App\Models\TRekeningPinjaman');
    }
	
}

