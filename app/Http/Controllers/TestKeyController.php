<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\SandiKantorCabang;
use App\Models\SandiBulan;
use App\Models\SandiTanggal;
use App\Models\SandiNominal;
use App\Models\KodeTransaksi;
use App\Models\TestKey;
use Session;
use Auth;

class TestKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		                        
        $LSandiKantorCabang = SandiKantorCabang::where('id_lembaga','=',Session::get('idLembaga'))->get();           

        $data = array(
            'title' => 'Validasi Test Key',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classFormSelect3' => 'single-select2',
            'classTable' => 'table table-sm table-bordered table-striped'            
        );         
                
        //return view('test_key/index', compact('data','LSandiKantorCabang'));        
        $returnHTML = view('test_key/index',compact('data','LSandiKantorCabang'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }            

    public function getKodePengirim(Request $request, $id)
    {   
        $LSandiKantorCabang = SandiKantorCabang::where('id','=',$id)
							->where('id_lembaga','=',Session::get('idLembaga'))
							->get();
        
        if($LSandiKantorCabang->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'sandiPengirim' => $LSandiKantorCabang[0]->sandi_pengirim,                
                ]);            
        }

    }
    
    public function getKodePenerima(Request $request, $id)
    {   
        $LSandiKantorCabang = SandiKantorCabang::where('id','=',$id)
							->where('id_lembaga','=',Session::get('idLembaga'))
							->get();     
        
        if($LSandiKantorCabang->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'sandiPenerima' => $LSandiKantorCabang[0]->sandi_penerima,                
                ]);            
        }

    }

    public function getKodeTglBulan(Request $request, $id)
    {           
        $tgl = date("j", strtotime($id));
        $bulan = date("m", strtotime($id));

        
        $LSandiTanggal = SandiTanggal::where('tanggal','=',$tgl)->get();     
        $LSandiBulan = SandiBulan::where('bulan','=',$bulan)->get();     
        
        if($LSandiTanggal->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'sandiTanggal' => $LSandiTanggal[0]->sandi,                
                'sandiBulan' => $LSandiBulan[0]->sandi,                
                ]);            
        }

    }

    public function getNominal(Request $request)
    {           
        $nominal = $request->nominal;
        $nominalNew = preg_replace("/,/","", trim($nominal));
        $jumlahKarakter = strlen($nominalNew);
        $pecahan = strlen($nominalNew);
        $kumpulanPecahan = array();
        $totalSandi = 0;

        for($a=0; $a<$jumlahKarakter; $a++){
            $karakter[$a] = substr($nominalNew,$a,1);
                //echo $karakter[$a].'<br>';
            if($karakter[$a] != 0) {
                $nominalPecahan[$a] = str_pad($karakter[$a], $pecahan, "0", STR_PAD_RIGHT);
                array_push( $kumpulanPecahan, $nominalPecahan[$a]);                
                //echo $nominalPecahan[$a].'<br>';
            }

            $pecahan--;
        }        
    
        for($b=0; $b<count($kumpulanPecahan); $b++){
            $LSandiNominal = SandiNominal::where('nominal','=',$kumpulanPecahan[$b])->get();
            $totalSandi += $LSandiNominal[0]->sandi;                        
        }
        
        if($totalSandi==0) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'sandiNominal' => $totalSandi,
            ]);            
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
            
            $cekData = TestKey::where([
                ['test_key_no','=',$request->no_bukti],
				['test_key_tgl','=',date('Y-m-d', strtotime($request->tgl))],
                ['id_kelas','=',Session::get('kelas')],
            ])->count();            

            if(($cekData)>0){
                return response()->json(['status'=>'insert_failed','msg'=>' Nomer Bukti Sudah Ada, Gunakan Nomer Yang Lain']); 

            } else {  
                
                $orderObj = DB::table('t_validasi_test_key')->select('test_key')->latest('test_key_id')->first();        
                if ($orderObj) {
                    $lastKodeNumber = explode('-',$orderObj->test_key);
                    $lastKodeNumber2 = $lastKodeNumber[0];
                    $KodeNumber2 = ($lastKodeNumber2 + 1);
                    
                } else {
                    $KodeNumber2 = 1;                 
                }
                
                $nominal = $request->nominal;
                $find = [","];
                $replace = [""];
                $newNominal = str_replace($find, $replace, $nominal);

                DB::beginTransaction();
                try {
                    $insert = TestKey::create([
                        "test_key_no"=> $request->no_bukti,
                        "test_key"=> $KodeNumber2.'-'.$request->hasil_test_key,
                        "test_key_tgl"=> date('Y-m-d', strtotime($request->tgl)),
                        "jenis_transfer"=> $request->id_transfer,
                        "id_cabang_pengirim"=> $request->cabang_pengirim,
                        "id_cabang_penerima"=> $request->cabang_penerima,
                        "hasil_test_key"=> "Tested",                    
                        "nominal"=> $newNominal,                                                            
                        "id_kelas"=> Session::get('kelas'),                        
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Auth::user()->name                 
                    ]);

                    if($insert) {
                        DB::commit();
                        return response()->json(['status'=>'insert_successful','id'=>$insert->test_key_id]);                
                    } else {
                        return response()->json(['status'=>'insert_failed','msg'=>'Insert Failed']);                
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
            if ($this->validateRequest($request, $id)->fails()) {

                // return response()->json([
                //     'status'=>'insert_failed',
                //     'error' => $this->validateRequest($request, $id)->messages()
                //     ]);
            }            
            
            $nominal = $request->nominal;
            $find = [","];
            $replace = [""];
            $newNominal = str_replace($find, $replace, $nominal);

            $update = TestKey::where('test_key_id', '=', $id)->update([              
                "test_key_no"=> $request->no_bukti,                
                "hasil_test_key"=> "Tested",                    
                "nominal"=> $newNominal,                                                            
                "id_kelas"=> Session::get('kelas'),                        
                "dt_modified"=> date("Y-m-d H:i:s"),
                "user_modified"=> Auth::user()->name                                                        
            ]);
            
            if($update) {
                return response()->json(['status'=>'insert_successful','id'=>$id]);                
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
            $query = TestKey::find($id)->delete();
            if($query) {
                return response()->json(['status'=>'delete_successful']);
            } else {
                return response()->json(['status'=>'delete_failed']);
            }
        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }
        

    public function search(Request $request)
    {   
        $bagian = base64_decode($request->bagian);
        $search = $request->kode;

        $searchData = TestKey::where('test_key_no','like','%' .$search . '%')
                        ->where('test_key_tgl','=',date('Y-m-d'))
                        ->get();          
        $pecah = explode("-",$searchData[0]->test_key);
        $testKeyKe = $pecah[0];
        $testKeyNilai = $pecah[1];

        if(count($searchData)>0) {
            return response()->json([
                'status'=>'oke',                
                'jbId'=> $searchData[0]->test_key_id,
                'jbNo'=> $searchData[0]->test_key_no,
                'jbTgl'=> $searchData[0]->test_key_tgl,            
                'jbCbngPengirim'=> $searchData[0]->id_cabang_pengirim,
                'jbCbngPenerima'=> $searchData[0]->id_cabang_penerima,
                'jbNominal'=> $searchData[0]->nominal,
                'jbIdTransfer'=> $searchData[0]->jenis_transfer,
                'jbTestKeyKe'=> $testKeyKe,
                'jbTestKeyNilai'=> $testKeyNilai,
                'jbHasil'=> $searchData[0]->hasil_test_key,
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }                
    }
}
