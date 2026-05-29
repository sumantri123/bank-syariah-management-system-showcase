<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penghasilan extends Model
{
    protected $table = 'm_penghasilan';

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

