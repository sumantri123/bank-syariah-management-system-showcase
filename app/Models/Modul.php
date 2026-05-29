<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;class Modul extends Model{    protected $table = 'm_modul';    protected $primaryKey = 'modul_id';    public $timestamps = false;
    protected $fillable = [		"modul_id",        "nama_modul",         "dt_record",        "dt_modified",        "user_record",        "user_modified"
    ];

    public function roles()    {        return $this->belongsToMany(Role::class, 'm_roles_moduls');    }}
