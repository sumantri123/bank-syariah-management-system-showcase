<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\TRekeningNasabah;
use Auth;
use Session;

class FasilitasPRKController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {	        

        $statusPrk = 'n';
        $LNasabahPRK = DB::table('t_rekening_nasabah as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')            
            ->select('a.id as tab_id', 'a.bunga', 'a.tanggal_buka', 'a.jangka', 'a.jenis_pembayaran', 'a.nomor_rekening', 'a.id_nasabah', 'm_nasabah.*')     
            ->where('a.prk','=',$statusPrk)
            ->where('id_jenis_rekening','=',1)
            ->where('a.id_kelas','=',Session::get('kelas'))
            ->where('nomor_rekening','like',Session::get('2XXGWADI').'%')              
            ->get();   

        $data = array(
            'title' => 'Input Fasilitas PRK',
            'subtitle' => Session::get('subtitle'),
            'pass' => Session::get('passAdmin'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'classTable' => 'table table-sm table-striped'
        );         
        
        //return view('fasilitas_prk/index', compact('data','LNasabahPRK'));    
        $returnHTML = view('fasilitas_prk/index',compact('data','LNasabahPRK'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );        

        
    }

    public function getData()
    {
        $statusPrk = 'y';
        $LNasabahPRK = DB::table('t_rekening_nasabah as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')            
            ->select('a.id as tab_id', 'a.prk', 'a.tanggal_buka', 'a.prk_nominal', 'a.nomor_rekening', 'a.id_nasabah', 'm_nasabah.*')     
            ->where('a.prk','=',$statusPrk)
            ->where('a.id_kelas','=',Session::get('kelas'))
            ->where('id_jenis_rekening','=',1)            
            ->get(); 
            

        if($LNasabahPRK) {
            return response()->json([
                'status'=>'oke',
                'data' => $LNasabahPRK
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }

    public function getDataNasabah(Request $request, $id)
    {
        //$nasabahIndividu = NasabahIndividu::where('status_nasabah','=',2)->get();
        $nasabahIndividu = DB::table('t_rekening_nasabah as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')            
            ->select('a.id as tab_id', 'a.tanggal_buka', 'a.nomor_rekening', 'm_nasabah.*')                        
            ->where('a.id','=',$id)
            ->where('id_jenis_rekening','=',1)            
            ->where('a.id_kelas','=',Session::get('kelas'))          
            ->get();
            

        if($nasabahIndividu) {
            return response()->json([
                'status'=>'oke',
                'nama' => $nasabahIndividu[0]->nama, 
                'id_rek' => $nasabahIndividu[0]->tab_id, 
                'cif' => $nasabahIndividu[0]->cif, 
                'tgl_buka' => $nasabahIndividu[0]->tanggal_buka
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
    
    
    private function validateRequest($request, $id=0){

        $messages = [
            'required' => 'Kolom <b>:attribute</b> harus diisi.',
            'min' => 'Panjang minimal <b>:attribute</b> huruf.',
            'numeric' => 'Inputan harus angka.',
            'unique' => 'Data <b>:attribute</b> ":input" sudah ada, tidak boleh sama.',
        ];

        return Validator::make($request->all(), [
//            "nomor_rekening" => "required|unique:t_rekening_nasabah,nomor_rekening".($id ? ",".$id.",id" : "" ),            
        ], $messages);
    }    


    public function update(Request $request)
    {
        if($request->ajax()){
            // if ($this->validateRequest($request, $id)->fails()) {

            //     return response()->json([
            //         'status'=>'insert_failed',
            //         'error' => $this->validateRequest($request, $id)->messages()
            //         ]);
            // }            
            $nominal = $request->plafon_prk;
            $find = [","];
            $replace = [""];
            $newNominal = str_replace($find, $replace, $nominal);

            $cekData = DB::table('t_rekening_nasabah')            
                        ->select('*')            
                        ->where('id','=',$request->id_rek)
                        ->where('prk','=','y')
                        ->get()
                        ->count();

            if($cekData>0){

                return response()->json(['status'=>'insert_failed','msg'=>'PRK Sudah Tercatat']);                

            } else {

                $update = TRekeningNasabah::where('id', '=',$request->id_rek)->update([              
                    "prk"=> "y",
                    "prk_nominal"=> $newNominal
                ]);

                if($update) {
                    return response()->json(['status'=>'insert_successful']);
                } else {
                    return response()->json(['status'=>'insert_failed','msg'=>'Gagal Tambah Data']);                
                }
            }            
            
        } else {
            return response()->json(['status'=>'proses_failed']);
        }

    }

    public function statusPrk(Request $request, $id)
    {
        if($request->ajax()){
            // if ($this->validateRequest($request, $id)->fails()) {

            //     return response()->json([
            //         'status'=>'insert_failed',
            //         'error' => $this->validateRequest($request, $id)->messages()
            //         ]);
            // }            

            $updateStatus = TRekeningNasabah::where('id', '=',$id)->update([              
                "prk"=> "n",
                "prk_nominal"=> null
            ]);

            if($updateStatus) {
                return response()->json(['status'=>'insert_successful']);
            } else {
                return response()->json(['status'=>'insert_failed']);
            }
        } else {
            return response()->json(['status'=>'proses_failed']);
        }
    }

    
}
