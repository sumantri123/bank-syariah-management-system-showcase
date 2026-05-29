<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SandiPemilik extends Model
{
    protected $table = 'm_sandi_pemilik';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        "id",
        "nama",
        "created_at",
        "updated_at"
    ];

    public function RekeningNasabah()
    {
        return $this->belongsTo('App\Models\TRekeningNasabah');
    }
}

