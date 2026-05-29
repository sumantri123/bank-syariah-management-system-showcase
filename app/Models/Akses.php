<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Akses extends Model{    protected $table = 'm_akses';
    protected $primaryKey = 'id';
    public $timestamps = false;    protected $fillable = [        "id",        "nama",        "dt_record",        "dt_modified",        "user_record",        "user_modified"    ];
   }
