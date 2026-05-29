<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\EditPerkiraan;
use App\Models\TRekeningNasabah;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use Session;
use Auth;

class TransaksiGiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index($kode)
    {		
        $LNasabahIndividu = DB::table('t_rekening_nasabah as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')            
            ->select('a.id as tab_id', 'a.bunga', 'a.id_perkiraan', 'a.tanggal_buka', 'a.jangka', 'a.jenis_pembayaran', 'a.nomor_rekening', 'a.id_nasabah', 'm_nasabah.*')            
            ->where('id_jenis_rekening','=',1)
            ->where('a.id_kelas','=',Session::get('kelas'))            
            ->get();  
                
        $LEditPerkiraan = EditPerkiraan::where('kode_perkiraan','like','300%')
						->where('id_lembaga','=',Session::get('idLembaga'))
						->get();         

        $data = array(
            'title' => 'Input Transaksi Rekening Giro',
            'subtitle' => Session::get('subtitle'),
            'pass' => Session::get('passAdmin'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classFormSelect3' => 'single-select2',
            'classTable' => 'table table-sm table-bordered table-striped',            
            'kode' => $kode
        );         
                
        //return view('transaksi_giro/index', compact('data','LEditPerkiraan','LNasabahIndividu'));        
        $returnHTML = view('transaksi_giro/index',compact('data','LEditPerkiraan','LNasabahIndividu'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }        

    public function getIdPerkiraan1(Request $request, $id)
    {   
        $LTRekeningNasabah = TRekeningNasabah::where('id','=',$id)->get();

        $dataPrk = DB::select(
            DB::raw('
                SELECT nama, nomor_rekening, df_trans_perkiraan, a.id, prk, prk_nominal,
                sum(CASE when c.id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit,
                sum(CASE when c.id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit         
                FROM t_rekening_nasabah as a 
                LEFT JOIN m_nasabah as b on a.id_nasabah = b.id                 
                LEFT JOIN t_jurnal_bagian_detail c on c.id_rekening = a.id
                LEFT JOIN m_perkiraan as d on a.id_perkiraan = d.id
                LEFT JOIN t_jurnal_bagian as e on c.id_jurnal_bagian = e.jurnal_bagian_id
                WHERE a.id_kelas = "'.Session::get('kelas').'"
                and id_jenis_rekening = 1
                and c.id_perkiraan = "'.Session::get('2XXGWADI_ID').'"
                and a.id = "'.$id.'"
				and id_lembaga = "'.Session::get('idLembaga').'"
                and jurnal_tanggal <= "'.date("Y-m-d").'"
                GROUP BY nama, nomor_rekening, df_trans_perkiraan, a.id, prk, prk_nominal
            ')
        );   
        
        if($LTRekeningNasabah->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'idPerkiraan' => $LTRekeningNasabah[0]['id_perkiraan'],
                'noRekening' => $LTRekeningNasabah[0]['nomor_rekening'],
                'prkNominal' => (isset($dataPrk[0]->prk_nominal)&&($dataPrk[0]->prk_nominal>=0)) ? $dataPrk[0]->prk_nominal : "0",
                'kredit' => (isset($dataPrk[0]->kredit)&&($dataPrk[0]->kredit>=0)) ? $dataPrk[0]->kredit : "0",
                'debet' => (isset($dataPrk[0]->debit)&&($dataPrk[0]->debit>-0)) ? $dataPrk[0]->debit : "0"

            ]);            
        }

    }

    public function getIdPerkiraan2(Request $request, $id)
    {   
        $LEditPerkiraan = EditPerkiraan::where('id','=',$id)
						->where('id_lembaga','=',Session::get('idLembaga'))
						->get();     
        
        if($LEditPerkiraan->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'kodePerkiraan' => $LEditPerkiraan[0]['kode_perkiraan']
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
                        
            $jurnalNo = $request->no_bukti;
            $nomer = $jurnalNo;  
            $bagianGrup = array("JM","JX","AK","CS","PB","JD","TF","JG","LA","AT");
            $bagian = base64_decode($request->bagian);  

            $cekData = JurnalBagian::where([
                ['jurnal_no','=',$nomer],
                ['jurnal_bagian','=',$bagian],
                ['kode_transaksi','=','OG'],
                ['jurnal_tanggal','=',date('Y-m-d', strtotime($request->tgl))],
                ['id_kelas','=',Session::get('kelas')],
            ])->count();
          
            
            if(($cekData)>0){
                return response()->json(['status'=>'insert_failed','msg'=>' Nomer Bukti Sudah Ada, Gunakan Nomer Yang Lain']); 

            } else if(in_array($bagian, $bagianGrup)){
                
                $pecah2 = explode('-',$request->rek_lawan_perk);
                $kodePerkiraan = $request->kode_perkiraan;                
                $pattern = substr($kodePerkiraan,-3);                

                if($pattern=="000"){

                    return response()->json(['status'=>'insert_failed','msg'=>'Tidak Diperbolehkan Menggunakan Kode Perkiraan 000']);

                }else {
                    
                    $nominal = $request->nominal;
                    $find = [","];
                    $replace = [""];
                    $newNominal = str_replace($find, $replace, $nominal);
                    $idTransaksi2 = ($request->id_transaksi == 1) ? 2:1;
                    $idPerkiraan = array($request->id_perkiraan1,$request->rek_lawan_perk);
                    $idJenisTransaksi = array($request->id_transaksi,$idTransaksi2);
                    $idRekening = array($request->id_rekening,null);
                    $sisaSaldo = $request->sisa_saldo;
                    $fasPrk = str_replace($find, $replace, $request->fasilitas_prk);
                    $total = $sisaSaldo + $fasPrk;

                    if(($idJenisTransaksi[0]==1) && ($newNominal > $total)){
                        return response()->json(['status'=>'insert_failed','msg'=>' Saldo Tidak Mencukupi']);    
                    } else {

                        DB::beginTransaction();
                        try {
                            $insert = JurnalBagian::create([
                                "jurnal_no"=> $jurnalNo,
                                "jurnal_keterangan"=> $request->keterangan,
                                "jurnal_tanggal"=> date('Y-m-d', strtotime($request->tgl)),
                                "jurnal_bagian"=> $bagian,    
                                "kode_transaksi"=> "OG",                
                                "id_kelas"=> Session::get('kelas'),                        
                                "dt_record"=> date("Y-m-d H:i:s"),
                                "user_record"=> Session::get('login_as')
                            ]);

                            for($a=0; $a<2;$a++){
                            
                                $insertDet = JurnalBagianDetail::create([
                                    "id_perkiraan"=> $idPerkiraan[$a],
                                    "id_jurnal_bagian"=> $insert->jurnal_bagian_id,
                                    "id_jenis_transaksi"=> $idJenisTransaksi[$a],
                                    "id_rekening"=> $idRekening[$a],
                                    "jurnal_det_nominal"=> $newNominal,
                                    "dt_record"=> date("Y-m-d H:i:s"),
                                    "user_record"=> Session::get('login_as'),   
                                ]);    
                                                            
                            }

                            if($insertDet) {
                                DB::commit();
                                return response()->json([
                                    'status'=>'insert_successful',
                                    'id'=>$insert->jurnal_bagian_id,
                                    'jbNo'=>$jurnalNo,
                                    'jbTgl'=>date('Y-m-d', strtotime($request->tgl)),
                                    'jbNoRekening'=>$request->no_rekening,
                                    'jbIdTransaksi'=>$request->id_transaksi,
                                    'jbNominal'=>$newNominal,
                                    ]);                
                            } else{
                                return response()->json(['status'=>'insert_failed','msg'=>'Insert Failed']);    
                            }
                        } catch (\Throwable $e) {
                            DB::rollback();            
                            throw $e;            
                            return response()->json(['status'=>'insert_failed']);
                        }
                    }                                                
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
            

            $bagianGrup = array("JM","JX","AK","CS","PB","JD","TF","JG","LA","AT");
            $bagian = base64_decode($request->bagian);                        
            
            $nominal = $request->nominal;
            $find = [","];
            $replace = [""];
            $newNominal = str_replace($find, $replace, $nominal);

            $update = JurnalBagian::where('jurnal_bagian_id', '=', $id)->update([                              
                "jurnal_keterangan"=> $request->keterangan,
                "jurnal_tanggal"=> date('Y-m-d', strtotime($request->tgl)),
                "jurnal_bagian"=> $bagian,                
                "id_kelas"=> Session::get('kelas'),
                "dt_modified"=> date("Y-m-d H:i:s"),
                "user_modified"=> Session::get('login_as')
            ]);

            
            if($update) {
                return response()->json([
                    'status'=>'insert_successful',
                    'id'=>$id,
                    'jbNo'=>$request->no_bukti,
                    'jbTgl'=>date('Y-m-d', strtotime($request->tgl)),
                    'jbNoRekening'=>$request->no_rekening,
                    'jbIdTransaksi'=>$request->id_transaksi,
                    'jbNominal'=>$newNominal,
                    ]);                                
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
    
    public function search(Request $request)
    {   
        $bagian = base64_decode($request->bagian);
        $search = $request->kode;

        $searchData = DB::table('t_jurnal_bagian as a')
            ->leftJoin('t_jurnal_bagian_detail as b', 'a.jurnal_bagian_id', '=', 'b.id_jurnal_bagian')
            ->leftJoin('m_perkiraan as c', 'b.id_perkiraan', '=', 'c.id')
            ->leftJoin('t_rekening_nasabah as d', 'b.id_rekening', '=', 'd.id')
            ->select('a.*', 'b.*', 'c.kode_perkiraan','c.nama_perkiraan','d.nomor_rekening')            
            ->where('jurnal_no', '=', $search)
            ->where('a.id_kelas', '=', Session::get('kelas'))
            ->where('jurnal_bagian','=',$bagian)
            ->where('a.kode_transaksi', '=', 'OG')
			->where('c.id_lembaga','=',Session::get('idLembaga'))
            ->where('jurnal_tanggal','=',date('Y-m-d'))
            ->get();

        if(count($searchData)>0) {
            return response()->json([
                'status'=>'oke',
                'jbId'=> $searchData[0]->jurnal_bagian_id,
                'jbNo'=> $searchData[0]->jurnal_no,
                'jbKet'=> $searchData[0]->jurnal_keterangan,
                'jbTgl'=> $searchData[0]->jurnal_tanggal,
                'jbBag'=> $searchData[0]->jurnal_bagian,
                'jbNominal'=> $searchData[0]->jurnal_det_nominal,
                'jbIdRekening'=> $searchData[0]->id_rekening,
                'jbIdPerkiraan1'=> $searchData[0]->id_perkiraan,
                'jbIdTransaksi'=> $searchData[0]->id_jenis_transaksi,
                'jbRekLawan'=> $searchData[1]->id_perkiraan,
                'jbRekKodeLawan'=> $searchData[1]->kode_perkiraan,
                'jbNoRekening'=> $searchData[0]->nomor_rekening,
                'data' => $searchData
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }                
    }
}
