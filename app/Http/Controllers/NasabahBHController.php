<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\NasabahIndividu;
use App\Models\JenisBadanUsaha;
use App\Models\SumberDana;
use App\Models\TRekeningNasabah;
use Auth;
use Session;

class NasabahBHController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {		
        $data = array(
            'title' => 'CIF Number Badan Usaha',
            'subtitle' => Session::get('subtitle'),
            'pass' => Session::get('passAdmin'),
            'btnAdd' => 'Tambah',
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classTable' => 'table table-sm table-striped',
            'cif_1' => '01',
            'cif_2' => '',
            'cif_3' => now()->year,
        ); 
        $LJenisBU = JenisBadanUsaha::get();
        $LSumberDana = SumberDana::where('status', '=', '1')->get();

        //return view('nasabah_badan_hukum/index', compact('data','LJenisBU','LSumberDana'));
        $returnHTML = view('nasabah_badan_hukum/index',compact('data','LJenisBU','LSumberDana'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }

    public function getDataNasabah()
    {
        $nasabahIndividu = DB::table('m_nasabah')            
            ->select('*')            
            ->where('status_nasabah','=',2)
            ->where('id_kelas', '=', Session::get('kelas'))
            // ->where(function ($query) {
            //     $query->where('id_kelas', '=', Session::get('kelas'))
            //           ->orWhere('id_kelas', '=', null);
            //     })

            ->orderBy('id', 'desc')
            ->get(); 

        if($nasabahIndividu) {
            return response()->json([
                'status'=>'oke',
                'data' => $nasabahIndividu
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }


    private function validateRequest($request, $id=0){

        $messages = [
            'required' => 'Kolom <b>:attribute</b> harus diisi.',
            'min' => 'Panjang minimal <b>:attribute</b> huruf.',
            'unique' => 'Data <b>:attribute</b> ":input" sudah ada, tidak boleh sama.',
        ];

        return Validator::make($request->all(), [
            "cif" => "required|unique:m_nasabah,cif".($id ? ",".$id.",id" : "" ),            
			// "nama" => "required",
            // "no_akta_pendirian" => "required",
            // "kegiatan_usaha" => "required",
            // "jenis_badan_usaha" => "required",
            // "anggaran_dasar" => "required",
            // "tanggal_lahir" => "required",            
            // "alamat_ktp" => "required",
            // "kota_ktp" => "required",            
            // "kodepos_ktp" => "required",
            // "telepon" => "required",            
            // "fax_perusahaan" => "required",            
            // "email_perusahaan" => "required",        
            // "siup" => "required",        
            // "npwp" => "required",
            // "tdp" => "required",
            // "ms_berlaku_tdp" => "required",            
            // "alamat_korespondensi" => "required",   
            // "alamat_domisili" => "required",             
            // "kota_domisili" => "required",                      
            // "kodepos_domisili" => "required", 
            // "telp_domisili" => "required",            
            // "fax_domisili" => "required",
            // "email_domisili" => "required",             
            // "negara_investor" => "required",             
            // "kuasa_pemegang_rekening_1" => "required",             
            // "kuasa_pemegang_rekening_2" => "required",             
            // "sumber_dana" => "required",
            // "masuk_dhbi" => "required",             
            // "tujuan_buka_rekening" => "required",             
            // "penggunaan_dana" => "required"
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

            //var_dump($request->post());
            $orderObj = DB::table('m_nasabah')->select('cif')->latest('id')->first();        
            if ($orderObj) {
                $lastKodeCif = explode('.',$orderObj->cif);
                $lastCif2 = $lastKodeCif[1];
                //$removed1char = substr($lastCif2, 1);

                if($lastKodeCif[2]!=date('Y')){
                    $cif_2 = str_pad(1, 5, "0", STR_PAD_LEFT);                 
                } else {
                    $cif_2 = str_pad($lastCif2 + 1, 5, "0", STR_PAD_LEFT);
                }
                
            } else {
                $cif_2 = str_pad(1, 5, "0", STR_PAD_LEFT);                 
            }

            $cifAll = '01.'.$cif_2.'.'.date('Y');
            //$cifAll = $request->cif_1.'.'.$request->cif_2.'.'.$request->cif_3;
            $statusNasabah = "2";

            DB::beginTransaction();
            try {

                $insert = NasabahIndividu::create([
                    "cif"=> $cifAll,
                    "nama"=> $request->nama_nasabah,				
                    "no_akta_pendirian"=> $request->no_akta,
                    "kegiatan_usaha"=> $request->kegiatan_usaha,
                    "jenis_badan_usaha"=> $request->id_jenis_badan_usaha,
                    "anggaran_dasar"=> $request->anggaran_dasar,
                    "tanggal_lahir"=> date('Y-m-d', strtotime($request->tgl)),
                    "alamat_ktp"=> $request->alamat_perusahaan,
                    "kota_ktp"=> $request->kota,				
                    "kodepos_ktp"=> $request->kodepos,                
                    "telepon"=> $request->telp,       
                    "fax_perusahaan"=> $request->fax,       
                    "email_perusahaan"=> $request->email,  
                    "siup"=> $request->siup,     
                    "npwp"=> $request->npwp,
                    "tdp"=> $request->tdp,
                    "ms_berlaku_tdp"=> date('Y-m-d', strtotime($request->ms_berlaku_tdp)),
                    "dt_record"=> date("Y-m-d H:i:s"),				
                    "user_record"=> Session::get('login_as'),          
                    "alamat_korespondensi"=> $request->jenis_korespondensi,                                
                    "alamat_domisili"=> $request->alamat_korespondensi,                
                    "kota_domisili"=> $request->kota_korespondensi,
                    "kodepos_domisili"=> $request->kodepos_korespondensi,
                    "telp_domisili"=> $request->telp_korespondensi,                                
                    "fax_domisili"=> $request->fax_korespondensi,
                    "email_domisili"=> $request->email_korespondensi,                       
                    "negara_investor"=> $request->investor,                
                    "kuasa_pemegang_rekening_1"=> $request->pemegang_rekening_1,                
                    "kuasa_pemegang_rekening_2"=> $request->pemegang_rekening_2, 
                    "sumber_dana"=> $request->sumber_dana,                
                    "masuk_dhbi"=> $request->dbhi,
                    "tujuan_buka_rekening"=> $request->tujuan_buka_rekening,
                    "status_nasabah"=> $statusNasabah,
                    "penggunaan_dana"=> $request->penggunaan_dana,
                    "id_kelas"=> Session::get('kelas')
                ]);

                if($insert) {
                    DB::commit();
                    return response()->json(['status'=>'insert_successful']);
                } else {
                    return response()->json(['status'=>'insert_failed']);
                }

            } catch (\Throwable $e) {
                DB::rollback();            
                throw $e;            
                return response()->json(['status'=>'insert_failed']);
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
            $cifAll = $request->cif_1.'.'.$request->cif_2.'.'.$request->cif_3; 
            $statusNasabah = "2";
            $update = NasabahIndividu::where('id', '=', $id)->update([
                "cif"=> $cifAll,
                "nama"=> $request->nama_nasabah,				
                "no_akta_pendirian"=> $request->no_akta,
                "kegiatan_usaha"=> $request->kegiatan_usaha,
                "jenis_badan_usaha"=> $request->id_jenis_badan_usaha,
                "anggaran_dasar"=> $request->anggaran_dasar,
                "tanggal_lahir"=> date('Y-m-d', strtotime($request->tgl)),
                "alamat_ktp"=> $request->alamat_perusahaan,
				"kota_ktp"=> $request->kota,				
				"kodepos_ktp"=> $request->kodepos,                
				"telepon"=> $request->telp,       
                "fax_perusahaan"=> $request->fax,       
                "email_perusahaan"=> $request->email,  
                "siup"=> $request->siup,     
                "npwp"=> $request->npwp,
                "tdp"=> $request->tdp,
                "ms_berlaku_tdp"=> date('Y-m-d', strtotime($request->ms_berlaku_tdp)),
                "dt_modified"=> date("Y-m-d H:i:s"),				
                "user_modified"=> Session::get('login_as'),               
                "alamat_korespondensi"=> $request->jenis_korespondensi,                                
                "alamat_domisili"=> $request->alamat_korespondensi,                
                "kota_domisili"=> $request->kota_korespondensi,
                "kodepos_domisili"=> $request->kodepos_korespondensi,
                "telp_domisili"=> $request->telp_korespondensi,                                
                "fax_domisili"=> $request->fax_korespondensi,
                "email_domisili"=> $request->email_korespondensi,                       
                "negara_investor"=> $request->investor,                
                "kuasa_pemegang_rekening_1"=> $request->pemegang_rekening_1,                
                "kuasa_pemegang_rekening_2"=> $request->pemegang_rekening_2, 
                "sumber_dana"=> $request->sumber_dana,                
                "masuk_dhbi"=> $request->dbhi,
                "tujuan_buka_rekening"=> $request->tujuan_buka_rekening,
                "status_nasabah"=> $statusNasabah,
                "penggunaan_dana"=> $request->penggunaan_dana,
                "id_kelas"=> Session::get('kelas')
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
            $cekRekening = TRekeningNasabah::where('id_nasabah','=',$id)->get()->count();

            if($cekRekening>0){
                return response()->json(['status'=>'delete_failed','msg'=>'Nasabah Mempunyai Rekening']);  
            } else {

                $query = NasabahIndividu::find($id)->delete();
                if($query) {
                    return response()->json(['status'=>'delete_successful']);
                } else {
                    return response()->json(['status'=>'delete_failed']);
                }
            }
        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }

    
}
