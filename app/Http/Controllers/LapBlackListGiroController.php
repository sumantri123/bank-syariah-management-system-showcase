<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\NasabahIndividu;
use App\Models\TRekeningNasabah;
use App\Models\BlackList;
use Auth;
use Session;

class LapBlackListGiroController extends Controller
{

    // use AuthenticatesUsers;
    protected $redirectTo = '/';

	public function __construct()
    {
        //$this->middleware('guest', ['except' => 'logout']);
    }

    public function index()
    {		
        $LNasabahIndividu = DB::table('t_rekening_nasabah as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')            
            ->select('a.id as tab_id', 'a.bunga', 'a.tanggal_buka', 'a.nomor_rekening', 'a.id_nasabah', 'a.sandi_pemilik', 'm_nasabah.*')            
            ->where('id_jenis_rekening','=',1)
            ->get();

        $data = array(
            'title' => 'Laporan Black List '.Session::get('subtitle'),
            'subtitle' => Session::get('subtitle'),
            
        );        
        //return view('lap_black_list/index', compact('data','LNasabahIndividu'));
        $returnHTML = view('lap_black_list/index',compact('data','LNasabahIndividu'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       
    
    public function getData()
    {
        //$nasabahIndividu = NasabahIndividu::where('status_nasabah','=',2)->get();
        $data = DB::table('t_blacklist_bi as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id') 
            ->leftJoin('t_rekening_nasabah', 'a.id_rekening', '=', 't_rekening_nasabah.id')            
            ->select('a.blacklist_id as blackList_id', 'a.id_nasabah', 'a.id_rekening', 't_rekening_nasabah.nomor_rekening', 't_rekening_nasabah.jenis_pembayaran', 'm_nasabah.*')                        
            ->get();
        if($data) {
            return response()->json([
                'status'=>'oke',
                'data' => $data
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
    
}
