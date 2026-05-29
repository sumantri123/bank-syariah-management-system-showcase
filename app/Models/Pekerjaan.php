<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    protected $table = 'm_pekerjaan';

    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        "id",
        "nama",
        "created_at",
        "updated_at"
    ];

    public function nasabahIndividu()
    {
        return $this->belongsTo('App\Models\NasabahIndividu');
    }
}

