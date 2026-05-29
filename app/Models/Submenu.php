<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    protected $table = 'm_submenu';

    protected $primaryKey = 'submenu_id';

    public $timestamps = false;
    protected $fillable = [
        "submenu_id",
        "submenu_child",
        "submenu_nama",
		"submenu_nama_alias",
		"submenu_session",
		"submenu_parent",
		"submenu_link",
		"submenu_param_1",
		"submenu_link_name",
		"submenu_icon",
		"submenu_order",
		"submenu_status",
    ];

    
}

