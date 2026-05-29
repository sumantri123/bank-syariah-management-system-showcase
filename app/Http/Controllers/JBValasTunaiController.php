<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\EditPerkiraan;
use App\Models\TRekeningNasabah;
use App\Models\JurnalBagian;
//use App\Models\NilaiTukar;
use App\Models\JurnalBagianDetail;
use App\Models\NilaiTukar;
use Session;
use Auth;

class JBValasTunaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		
	    
		$LEditPerkiraan = EditPerkiraan::where('id_lembaga','=',Session::get('idLembaga'))->get();             
	    $LKursTukar = NilaiTukar::where('id_kelas','=',Session::get('kelas'))
		    ->where('kurs_nama','<>','Kurs TT')
		    ->get();

        $data = array(
            'title' => 'Jual Beli Valuta Asing',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',            
        );         
                        
        //return view('transaksi_jb_valas_tunai/index', compact('data','LEditPerkiraan'));        
        $returnHTML = view('transaksi_jb_valas_tunai/index',compact('data','LEditPerkiraan','LKursTukar'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    
    
    public function getIdPerkiraan(Request $request)
    {   
        $search = $request->search;

        if($search == ''){
            $LEditPerkiraan = EditPerkiraan::where('id_lembaga','=',Session::get('idLembaga'))->orderby('kode_perkiraan','asc')->select('id','kode_perkiraan','nama_perkiraan')->limit(10)->get();
        }else{                
            $LEditPerkiraan = EditPerkiraan::orderby('kode_perkiraan','asc')
                ->select('id','kode_perkiraan','nama_perkiraan')   
                ->where('kode_perkiraan', 'like', $search . '%')
				->where('id_lembaga','=',Session::get('idLembaga'))
                ->where(function($query){                                                        
                    $query->Where('kode_perkiraan', 'NOT LIKE', '5%');
                    $query->Where('kode_perkiraan', 'NOT LIKE', '6%');
                    })                
                ->limit(10)
                ->get();
        }

        $response = array();
        if($LEditPerkiraan->isEmpty()) {
                $response[] = array("value"=>"0","label"=>"Note.Kode Tidak Ada");
        } else {
            foreach($LEditPerkiraan as $EditPerkiraan){
                $response[] = array("value"=>$EditPerkiraan->id,"label"=>$EditPerkiraan->kode_perkiraan.' - '.$EditPerkiraan->nama_perkiraan);                
            }
        }

        return response()->json($response);
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
            
            $jurnalNo = $request->no_bukti;
            $nomer = $jurnalNo; 
            $bagianGrup = array("JM","JX","AK","CS","PB","JD","TF","JG","LA","AT");
            //$bagian = base64_decode($request->bagian);
            $bagian = "AT";

            $cekData = JurnalBagian::where([
                ['jurnal_no','=',$nomer],
                ['jurnal_bagian','=',$bagian],
                ['kode_transaksi','=','LV'],
                ['jurnal_tanggal','=',date('Y-m-d', strtotime($request->tgl))],
                ['id_kelas','=',Session::get('kelas')],
            ])->count();
            
            
            if(($cekData)>0){
                return response()->json(['status'=>'insert_failed','msg'=>' Nomer Bukti Sudah Ada, Gunakan Nomer Yang Lain']); 
            
            } else if(in_array($bagian, $bagianGrup)){
                
                DB::beginTransaction();
                try {
                    $insert = JurnalBagian::create([
                        "jurnal_no"=> $jurnalNo,
                        "jurnal_keterangan"=> $request->keterangan,
                        "jurnal_tanggal"=> date('Y-m-d', strtotime($request->tgl)),
                        "jurnal_bagian"=> $bagian,  
                        "kode_transaksi"=> "LV",                  
                        "id_nilai_tukar"=> $request->transaksi,                                    
                        "id_kelas"=> Session::get('kelas'),
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Session::get('login_as')
                    ]);

                    if($insert) {
                        DB::commit();
                        return response()->json(['status'=>'insert_successful','id'=>$insert->jurnal_bagian_id,'no'=>$jurnalNo]);                
                    } else {
                        return response()->json(['status'=>'insert_failed','msg'=>'Insert Failed']);                
                    }
                } catch (\Throwable $e) {
                    DB::rollback();            
                    throw $e;            
                    return response()->json(['status'=>'insert_failed']);
                }

            } else {
                return response()->json(['status'=>'insert_failed','msg'=>' Akses Ditolak, Silahkan Refresh Halaman']);                
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
                     
            //$bagian = base64_decode($request->bagian);
            $bagian = "AT";
            $update = JurnalBagian::where('jurnal_bagian_id', '=', $id)->update([                              
                "jurnal_keterangan"=> $request->keterangan,
                "jurnal_tanggal"=> date('Y-m-d', strtotime($request->tgl)),
                "jurnal_bagian"=> $bagian,                    
                "id_kelas"=> Session::get('kelas'),
                "dt_modified"=> date("Y-m-d H:i:s"),
                "user_modified"=> Session::get('login_as')
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
            $query = JurnalBagian::find($id)->delete();
            if($query) {
                return response()->json(['status'=>'delete_successful']);
            } else {
                return response()->json(['status'=>'delete_failed']);
            }
        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }
    

    // untuk jurnal bagian detail

    public function storeDet(Request $request)
    {
        if($request->ajax()){
            // if ($this->validateRequest($request)->fails()) {
			// 	return response()->json([
            //         'status'=>'insert_failed',
            //         'error' => $this->validateRequest($request)->messages()
            //         ]);

            // }
            $kodePerkiraan = $request->kodePer;
            $pattern = substr($kodePerkiraan,-3);

            if($pattern=="000"){

                return response()->json(['status'=>'insert_failed','msg'=>'Tidak Diperbolehkan Menggunakan Kode Perkiraan 000']);
                
            } else {

                $idKurs = explode(".",$request->transaksi);
                $LNilaiTukar = NilaiTukar::where('id','=',$idKurs[0])
                                ->where('id_kelas','=',Session::get('kelas'))                                
                                ->get();

                if(count($LNilaiTukar)>0) {
                    $kursJual = $LNilaiTukar[0]->kurs_jual;
                    $kursBeli = $LNilaiTukar[0]->kurs_beli;                
                    $kursTengah = ($kursJual + $kursBeli)/2;
                    $kurs = ($idKurs[1]==1) ? $kursJual:$kursBeli; // 1: Jual; 2: Beli		
                    $statusEndSelisihJB = ($idKurs[1]==1) ? Session::get('1XXKAVAL'):Session::get('1XXKATEL');
                    $newNominalValas = str_replace(",","",$request->nominal);

                    $cariData = DB::table('t_jurnal_bagian_detail')->where('id_jurnal_bagian','=',$request->idJB)->count();        
					
                    if($idKurs[1]==1){
						$newNominalRp = ($cariData>0) ? $newNominalValas * $kursTengah : $newNominalValas * $kurs;                					
					} else {
						$newNominalRp = ($cariData>0) ? $newNominalValas * $kurs : $newNominalValas * $kursTengah ;                					
					}

                    $insert = JurnalBagianDetail::create([
                        "id_perkiraan"=> $request->idPer,
                        "id_jurnal_bagian"=> $request->idJB,
                        "id_jenis_transaksi"=> $request->idTrans,
                        "jurnal_det_nominal"=> $newNominalRp,
                        "jurnal_det_nominal_valas"=> $newNominalValas,
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Session::get('login_as'),   
                    ]);
                            
                    if($cariData > 0){

                        $cekNominalValas = JurnalBagianDetail::where('id_jurnal_bagian','=',$request->idJB)
                                            ->orderby('jurnal_det_id','asc')                
                                            ->get();
                        $dataRp1 = $cekNominalValas[0]->jurnal_det_nominal;
                        $dataRp2 = $cekNominalValas[1]->jurnal_det_nominal;
                        $dataVl1 = $cekNominalValas[0]->jurnal_det_nominal_valas;
                        $dataVl2 = $cekNominalValas[1]->jurnal_det_nominal_valas;
                        

                        $insertSelisihJB = JurnalBagianDetail::create([
                            "id_perkiraan"=> Session::get('4XPVALAS_ID'),
                            "id_jurnal_bagian"=> $request->idJB,
                            "id_jenis_transaksi"=> 2,
                            "jurnal_det_nominal"=> ($dataRp1 - $dataRp2),
                            "jurnal_det_nominal_valas"=> ($dataVl1 - $dataVl2),
                            "dt_record"=> date("Y-m-d H:i:s"),
                            "user_record"=> Session::get('login_as'),   
                        ]);

                    }

                    if($insert) {
                        return response()->json(['status'=>'insert_successful','id'=>$insert->jurnal_det_id]);                                    
                    } else {
                        return response()->json(['status'=>'insert_failed']);
                    }
                } else {
                    return response()->json(['status'=>'insert_failed','msg'=>'Setup Nilai Tukar Di Kelas Ini Tidak Ada']);
                }
            }            
        } else {
            return redirect('asset/');
        }

    }

    // public function destroyDet(Request $request, $id, $idJB)
    // {
    //     $countData = JurnalBagianDetail::where('id_jurnal_bagian','=',$idJB)->get()->count();
    //     $message = ($countData>1) ? "delete_successful":"delete_successfulx";

    //     if($request->ajax()){
    //         if($countData>1){
    //             $query = JurnalBagianDetail::find($id)->delete();
    //         } else {
    //             $query = JurnalBagian::find($idJB)->delete();
    //         }
    //         if($query) {
    //             return response()->json(['status'=>$message]);
    //         } else {
    //             return response()->json(['status'=>'delete_failed']);
    //         }                    

    //     } else {
    //         return response()->json(['status'=>'delete_failed']);
    //     }
    // }

    public function totalDet(Request $request, $id)
    {
        $totalDK = DB::table('t_jurnal_bagian_detail as a')                        
            ->where('a.id_jenis_transaksi','=',1)
            ->where('a.id_jurnal_bagian','=',$id)
            ->sum('a.jurnal_det_nominal_valas');  
            
        $totalKR = DB::table('t_jurnal_bagian_detail as a')                        
            ->where('a.id_jenis_transaksi','=',2)
            ->where('a.id_jurnal_bagian','=',$id)
            ->sum('a.jurnal_det_nominal_valas');  

        if($totalDK || $totalKR) {
            return response()->json([
                'status'=>'oke',                
                'totDebet' => $totalDK,
                'totKredit' => $totalKR
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }
    }

    public function search(Request $request)
    {   
        $bagianGrup = array("JM","JX","AK","CS","PB","JD","TF","JG","LA","AT","LV");
        $bagian = "AT";
        $search = $request->kode;

        $searchData = DB::table('t_jurnal_bagian as a')
            ->leftJoin('t_jurnal_bagian_detail as b', 'a.jurnal_bagian_id', '=', 'b.id_jurnal_bagian')
            ->leftJoin('m_perkiraan as c', 'b.id_perkiraan', '=', 'c.id')
            ->select('a.*', 'b.*', 'c.kode_perkiraan','c.nama_perkiraan')            
            ->where('jurnal_no', '=', $search)            
            ->where('a.kode_transaksi', '=', 'LV') 
            ->where('id_kelas', '=', Session::get('kelas'))
            ->where('jurnal_bagian','=',$bagian)
            ->where('jurnal_tanggal','=',date('Y-m-d'))
			->where('c.id_lembaga', '=', Session::get('idLembaga'))
            //->where('kode_perkiraan','!=','705009')
            ->where('flag','=',1)
            ->get();

        if(count($searchData)>0) {
            return response()->json([
                'status'=>'oke',
                'jbId'=> $searchData[0]->jurnal_bagian_id,
                'jbNo'=> $searchData[0]->jurnal_no,
                'jbKet'=> $searchData[0]->jurnal_keterangan,
                'jbTgl'=> $searchData[0]->jurnal_tanggal,
                'jbBag'=> $searchData[0]->jurnal_bagian,
                'data' => $searchData
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }                
    }
}
