<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use Notifiable;

    protected $table = 'm_member'; 
    
    protected $primaryKey = 'member_id';

    public $timestamps = false;

    protected $fillable = [	
        "member_id",
        "user_id",
        "id_kategori_peserta",
        "nama",
        "alamat",
        "jenis_identitas",
        "foto_identitas",
        "asal_institusi",
        "pekerjaan",
        "email",
        "is_verified",
        "no_hp",
        "dt_record",
        "dt_modified",
        "user_record",
        "user_modified"
    ];
 

    public function getFotoIdentitasAttribute()
    {
        return $this->attributes['foto_identitas'] ? $this->attributes['foto_identitas'] : '/asset/karyawan/foto_identitas/default.jpg';
    }
 
}
