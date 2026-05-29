<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EditPerkiraan;
use Auth;
use Session;

class LapDafPerkiraanController extends Controller
{

    // use AuthenticatesUsers;
    protected $redirectTo = '/';

	public function __construct()
    {
        //$this->middleware('guest', ['except' => 'logout']);
    }

    public function index()
    {		
        $data = array(
            'title' => 'Laporan Daftar Perkiraan',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
        );        
        //return view('lap_daftar_perkiraan/index', compact('data'));
        $returnHTML = view('lap_daftar_perkiraan/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
        $EditPerkiraan = EditPerkiraan::where('id_lembaga','=',Session::get('idLembaga'))
						->orderBy('kode_perkiraan', 'ASC')->get();

        if($EditPerkiraan) {
            return response()->json([
                'status'=>'oke',
                'data' => $EditPerkiraan
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
        
}
