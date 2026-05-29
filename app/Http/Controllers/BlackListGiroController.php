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

class BlackListGiroController extends Controller
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
            ->where(function ($query) {
                $query->where('a.id_kelas', '=', Session::get('kelas'))
                      ->orWhere('a.id_kelas', '=', null);
                    })            
            ->whereNotIn('a.id', DB::table('t_blacklist_bi')->pluck('id_rekening'))
            ->get();        

        $data = array(
            'title' => 'Black List '.Session::get('subtitle'),
            'subtitle' => Session::get('subtitle'),
            'pass' => Session::get('passAdmin'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'classTable' => 'table table-sm table-striped',            
        );
        //return view('black_list/index', compact('data','LNasabahIndividu'));
        $returnHTML = view('black_list/index',compact('data','LNasabahIndividu'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );        
        
    }       
    
    public function getData()
    {
        //$nasabahIndividu = NasabahIndividu::where('status_nasabah','=',2)->get();
        $data = DB::table('t_blacklist_bi as a')
            ->select('a.blacklist_id as blackList_id', 'a.id_nasabah', 'a.id_rekening', 'b.id as tab_id', 'b.nomor_rekening', 'b.jenis_pembayaran', 'm_nasabah.*')                        
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id') 
            ->leftJoin('t_rekening_nasabah as b', 'a.id_rekening', '=', 'b.id')                        
            ->where('a.id_kelas','=',Session::get('kelas'))            
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

    public function getDataNasabah(Request $request, $id)
    {   
        $LNasabahIndividu = DB::table('t_rekening_nasabah as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')            
            ->select('a.id as tab_id', 'a.nomor_rekening', 'm_nasabah.*')            
            ->where('a.id','=',$id)
            ->get();     
        
        if($LNasabahIndividu->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'nama' => $LNasabahIndividu[0]->nama,
                'tab_id' => $LNasabahIndividu[0]->tab_id,
                'alamat' => $LNasabahIndividu[0]->alamat_ktp,
                'kota' => $LNasabahIndividu[0]->kota_ktp,                
                'id' => $LNasabahIndividu[0]->id,                
                ]);            
        }

    }

    private function validateRequest($request, $id=0){

        $messages = [
            'required' => 'Kolom <b>:attribute</b> harus diisi.',
            'min' => 'Panjang minimal <b>:attribute</b> huruf.',
            'unique' => 'Data <b>:attribute</b> ":input" sudah ada, tidak boleh sama.',
        ];

        return Validator::make($request->all(), [
            //"kode_perkiraan" => "required|unique:m_perkiraan,kode_perkiraan".($id ? ",".$id.",id" : "" ),            
        ], $messages);
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            // if ($this->validateRequest($request)->fails()) {
			// 	return response()->json([
            //         'status'=>'insert_failed',
            //         'error' => $this->validateRequest($request)->messages()
            //         ]);
            // }
            $cekBlackList = BlackList::where('id_rekening','=',$request->rekening_nasabah_id)->get()->count();

            if($cekBlackList>0){
                return response()->json(['status'=>'insert_failed','msg'=>'No. Rekening Sudah DiBlacklist, Silahkan Refresh Halaman']);  
            } else {

                DB::beginTransaction();

                try {
                    $insert = BlackList::create([                
                        "id_rekening"=> $request->rekening_nasabah_id,								
                        "id_nasabah"=> $request->id_nasabah,
                        "user_record"=> Session::get('login_as'),
                        "id_kelas"=> Session::get('kelas'),
                        "dt_record"=> date("Y-m-d H:i:s")
                    ]);
        
                    if($insert) {
                        DB::commit();
                        return response()->json(['status'=>'insert_successful']);
                    } else {
                        return response()->json(['status'=>'insert_failed','msg'=>'']);  
                    }
                    
                } catch (\Throwable $e) {

                    DB::rollback();            
                    throw $e;            
                    return response()->json(['status'=>'insert_failed']);
    
                }
            }
            
        } else {
            return redirect('asset/');
        }

    }

    public function update(Request $request, $id)
    {
        if($request->ajax()){
            // if ($this->validateRequest($request, $id)->fails()) {

            //     return response()->json([
            //         'status'=>'insert_failed',
            //         'error' => $this->validateRequest($request, $id)->messages()
            //         ]);
            // }

            $update = BlackList::where('blacklist_id', '=', $id)->update([ 
                "id_rekening"=> $request->rekening_nasabah_id,	                               
                "id_nasabah"=> $request->id_nasabah,
                "user_modified"=> Session::get('login_as'), 
                "id_kelas"=> Session::get('kelas'),                                               
                "dt_modified"=> date("Y-m-d H:i:s")
            ]);

            if($update) {
                return response()->json(['status'=>'insert_successful']);
            } else {
                return response()->json(['status'=>'insert_failed']);
            }
        } else {
            return response()->json(['status'=>'proses_failed']);
        }

    }

    public function destroy(Request $request, $id)
    {
        if($request->ajax()){
            $query = BlackList::find($id)->delete();
            if($query) {
                return response()->json(['status'=>'delete_successful']);
            } else {
                return response()->json(['status'=>'delete_failed']);
            }
        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }

}
