<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TRekeningPinjaman extends Model
{
    protected $table = 't_rekening_pinjaman';

    protected $primaryKey = 'rekening_pinjaman_id';

    public $timestamps = false;
    protected $fillable = [
        'rekening_pinjaman_id',
		'jenis_pinjaman',
		'id_rekening',
		'id_gol_debitur',
		'id_sifat_kredit',
		'id_jenis_penggunaan',
		'id_sektor_ekonomi',
		'id_keterkaitan',
		'id_sumber_dana',
		'id_periode_bayar',
		'id_lokasi_debitur',
		'id_penjamin',
		'id_kelas',
		'id_jenis_usaha',
		'id_jenis_anggunan',
		'id_ikatan',		
		'ikatan_persen',
		'bagian_dijamin',
		'nilai_agunan',
		'taksasi_agunan',
		'tanggal_realisasi',
		'angka_realisasi',
		'jangka_waktu',
		'bunga_efektif_anuitas',
		'bunga_efektif_bulan',
		'nominal_efektif_anuitas',
		'nominal_pokok',
		'provisi_persen',
		'provisi_nominal',
		'angsuran_bulan',
		'nomor_pk',		
		'id_perkiraan_provisi',
		'bukti_provisi',
		'id_perkiraan_dropping',
		'id_perkiraan_lawan',
		'bukti_dropping',
		'kode_ao',
		'keterangan',		
		'dt_record',
		'user_record',
		'dt_modified',
		'user_modified'		
    ];


	public function kelas(){
		return $this->hasOne('App\Models\Kelas');
	}

	public function golonganDebitur(){
		return $this->hasOne('App\Models\EditPerkiraan');
	}
   
	public function sifatKredit(){
		return $this->hasOne('App\Models\JenisRekening');
	}

	public function jenisPenggunaan(){
		return $this->hasOne('App\Models\NasabahIndividu');
	}

	public function sektorEkonomi(){
		return $this->hasOne('App\Models\JenisPinjaman');
	}

	public function sumberDana(){
		return $this->hasOne('App\Models\SandiPemilik');
	}

	public function periodeBayar(){
		return $this->hasOne('App\Models\SandiPemilik');
	}

	public function lokasiDebitur(){
		return $this->hasOne('App\Models\SandiPemilik');
	}

	public function penjamin(){
		return $this->hasOne('App\Models\SandiPemilik');
	}

	public function jenisUsaha(){
		return $this->hasOne('App\Models\SandiPemilik');
	}

	public function jenisAngunan(){
		return $this->hasOne('App\Models\SandiPemilik');
	}

	public function ikatan(){
		return $this->hasOne('App\Models\SandiPemilik');
	}
	
	public function PinjamanJaminan()
    {
        return $this->belongsTo('App\Models\PinjamanJaminan');
    }

	public function RekeningPinjamanAngsuran()
    {
        return $this->belongsTo('App\Models\TRekeningAngsuranPinjaman');
    }
}

