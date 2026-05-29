<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisIdentitas extends Model
{
    protected $table = 'm_jenis_identitas';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        "id",
        "name",
        "created_at",
        "updated_at"
    ];

    public function nasabahIndividu()
    {
        return $this->belongsTo('App\Models\NasabahIndividu');
    }
}

