<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TRekeningAngsuranPinjaman extends Model
{
    protected $table = 't_rekening_pinjaman_angsuran';

    protected $primaryKey = 'pinjaman_angsuran_id';

    public $timestamps = false;
    protected $fillable = [
        'pinjaman_angsuran_id',
		'id_rekening_pinjaman',
		'id_rekening',
		'angsuran_ke',
		'tanggal_jth_tempo',
		'estimasi',
		'saldo_awal',
		'suku_bunga_efektif',
		'angsuran_pokok',
		'tagihan_bunga',
		'amortisasi',
		'saldo_akhir',
		'status',
		'tanggal_bayar',		
		'dt_record',
		'user_record',
		'dt_modified',
		'user_modified'		
    ];
	
	public function RekeningPinjaman()
    {
        return $this->hasOne('App\Models\TRekeningPinjaman');
    }
}

