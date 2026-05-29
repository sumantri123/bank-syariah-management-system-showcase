<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use Notifiable;

    protected $table = 'm_karyawan'; 
    
    protected $primaryKey = 'karyawan_id';

    public $timestamps = false;

    protected $fillable = [
        "karyawan_id",
        "user_id",
        "nama",
        "alamat",
        "jenis_identitas",
        "foto_identitas",
        "asal_institusi",
        "pekerjaan",
        "email",
        "no_hp",	
        "dt_record",
        "dt_modified",
        "user_record",
        "user_modified"
    ];

    public function user()
    {
    	return $this->hasOne(User::class, 'id','user_id');
    }
 
    protected $hidden = [
        'password', 'remember_token',
    ];

    
    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');   
    }

    public function getFotoIdentitasAttribute()
    {
        return $this->attributes['foto_identitas'] ? $this->attributes['foto_identitas'] : '/asset/karyawan/foto_identitas/default.jpg';
    }
 
}
