<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPinjaman extends Model
{
    protected $table = 'm_jenis_pinjaman';

    protected $primaryKey = 'pinjaman_id';

    public $timestamps = false;
    protected $fillable = [
        "pinjaman_id",
        "pinjaman_nama",
        'dt_record',
		'user_record',
		'dt_modified',
		'user_modified'
    ];

    public function RekeningNasabah()
    {
        return $this->belongsTo('App\Models\TRekeningNasabah');
    }
}

