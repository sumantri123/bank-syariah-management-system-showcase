<?php
namespace App\Http\ViewComposer;
use Illuminate\View\View;use App\Models\Karyawan; use App\Models\User; use Carbon\Carbon;use App\Models\UserAuth\Permission;use App\Models\Role; use Auth;use DB;
class SidebarViewComposer{ 
  public function __construct()  { 	
  }
  public function compose(View $view)  {    $user = Auth::user();	    $role = Role::with([			'moduls' => function($q) {			$q->join('m_modul as m','m.modul_id','=','m_roles_moduls.modul_id')
          ->select('m_roles_moduls.*', 'm.nama_modul');
      }
  ])->find($user->id_role);
    $view->with(['nama_role' => $role->nama_role,'modul'=>$role->moduls->toArray()]);
  }
}
