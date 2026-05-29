<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SumberDana extends Model
{
    protected $table = 'm_sumber_dana';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        "id",
        "kode",
        "nama",
        "status",
        "created_at",
        "updated_at"
    ];

    public function nasabahIndividu()
    {
        return $this->belongsTo('App\Models\NasabahIndividu');
    }

    public function RekeningPinjaman()
    {
        return $this->belongsTo('App\Models\TRekeningPinjaman');
    }
}

