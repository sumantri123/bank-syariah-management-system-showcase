<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Crypt;
use Session;
use DB;

class SiteHelpers
{
	public static function main_menu(){
				

		$main_menu = DB::table('m_submenu as a')        
		->join('m_submenu_det as b', 'a.submenu_id', '=', 'b.id_sub_menu')        		
		->join('m_submenu as c', 'a.submenu_parent', '=', 'c.submenu_id')        
        ->select('c.submenu_nama','a.submenu_parent','c.submenu_link','c.submenu_icon','c.submenu_session')		
		->where('a.submenu_status', '=', 'y')				
		->where('b.id_kelas', '=', Session::get('kelas'))
		->where('c.submenu_child', '=', 'n')				
		->groupBy('c.submenu_nama','a.submenu_parent','c.submenu_link','c.submenu_icon','c.submenu_session')
		->orderBy('c.submenu_order','asc')
        ->get();
 		
        return $main_menu;
	}

	public static function side_menu($id){
				
		$side_menu = DB::table('m_submenu_det as a')
        ->join('m_submenu as b', 'a.id_sub_menu', '=', 'b.submenu_id')        		
        ->select('*')
		->where('a.id_kelas', '=', Session::get('kelas'))			
		->whereIn('b.submenu_child', ['y', 'n1'])		
		->where('b.submenu_status', '=', 'y')
		->where('b.submenu_parent', '=', $id)			
		->orderBy('submenu_order','asc')
        ->get();
		
        return $side_menu;
	}
	
	public static function side_menu_2($id){
				
		$side_menu = DB::table('m_submenu_det as a')
        ->join('m_submenu as b', 'a.id_sub_menu', '=', 'b.submenu_id')        		
        ->select('*')
		->where('a.id_kelas', '=', Session::get('kelas'))			
		->whereIn('b.submenu_child', ['y','n2'])		
		->where('b.submenu_status', '=', 'y')
		->where('b.submenu_parent', '=', $id)			
		->orderBy('submenu_order','asc')
        ->get();
		
        return $side_menu;
	}
	
	public static function side_menu_3($id){
				
		$side_menu = DB::table('m_submenu_det as a')
        ->join('m_submenu as b', 'a.id_sub_menu', '=', 'b.submenu_id')        		
        ->select('*')
		->where('a.id_kelas', '=', Session::get('kelas'))			
		->whereIn('b.submenu_child', ['y'])		
		->where('b.submenu_status', '=', 'y')
		->where('b.submenu_parent', '=', $id)			
		->orderBy('submenu_order','asc')
        ->get();
		
        return $side_menu;
	}
}
