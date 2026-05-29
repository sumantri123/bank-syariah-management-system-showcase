<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\EditPerkiraan;
use App\Models\TRekeningNasabah;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use App\Models\KodeTransaksi;
use Session;
use Auth;

class TransaksiPembayaranTunaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index($kode)
    {		
        /*$LNasabahIndividu = DB::table('t_rekening_nasabah as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')            
            ->select('a.id as tab_id', 'a.bunga', 'a.id_perkiraan', 'a.tanggal_buka', 'a.jangka', 'a.jenis_pembayaran', 'a.nomor_rekening', 'a.id_nasabah', 'm_nasabah.*')
            ->where('a.id_kelas','=',Session::get('kelas'))  
            ->whereIn('a.id_jenis_rekening', [1, 2]) 
            ->orderBy('a.id_jenis_rekening', 'asc')  
            ->orderBy('a.nomor_rekening', 'asc')       
            ->get();  */
	

		/*$first = DB::table('perkiraanview')
					->select('nomor_rekening', 'nama', 'id_rekening as tab_id ','id_perkiraan');
		$LNasabahIndividu = DB::table('t_rekening_nasabah as a')
				 ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')
				->select('nomor_rekening', 'nama', 'a.id as tab_id','a.id_perkiraan')
					->union($first)
					->get();
		*/
		$LNasabahIndividu = DB::select(
                    DB::raw("
                            select nomor_rekening, nama, id_rekening as tab_id ,id_perkiraan
                            from perkiraanview a
							left join m_perkiraan b on a.id_perkiraan = b.id
							where right(nomor_rekening, 3) <> '000' and b.id_lembaga = ".Session::get('idLembaga')."
							union
							select nomor_rekening, nama, a.id as tab_id ,a.id_perkiraan
							from t_rekening_nasabah as a
                            left join m_nasabah on a.id_nasabah = m_nasabah.id
							where a.id_kelas =".Session::get('kelas')."
							and a.id_jenis_rekening in (1, 2, 3)
                            
							
                    ")
                );
            
			
		//var_dump($LNasabahIndividu);
                
        $LKodeTransaksi = KodeTransaksi::where('jenis_transaksi','=','1')->get();           

        $data = array(
            'title' => 'Transaksi Pembayaran Tunai',
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
                
        //return view('transaksi_pembayaran_tunai/index', compact('data','LKodeTransaksi','LNasabahIndividu'));        
        $returnHTML = view('transaksi_pembayaran_tunai/index',compact('data','LKodeTransaksi','LNasabahIndividu'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }            

    public function getIdPerkiraan1(Request $request, $id)
    {   
        $LTRekeningNasabah = TRekeningNasabah::where('id','=',$id)->get();     
        
        if($LTRekeningNasabah->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'idPerkiraan' => $LTRekeningNasabah[0]['id_perkiraan'],
                'noRekening' => $LTRekeningNasabah[0]['nomor_rekening']
                ]);            
        }

    }
    
    public function getKodeTransaksi(Request $request, $id)
    {   
        $LKodeTransaksi = KodeTransaksi::where('id','=',$id)->get();     
        
        if($LKodeTransaksi->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'kodeTransaksi' => $LKodeTransaksi[0]['kode_transaksi'],
                'namaTransaksi' => $LKodeTransaksi[0]['nama_transaksi']
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
            if($request->id_rekening2=="0") $request->id_rekening2 = null;
            $jurnalNo = $request->no_bukti;
            $nomer = $jurnalNo;         
            
            $bagianGrup = array("JM","JX","AK","CS","PB","JD","TF","JG","LA","AT");
            $bagian = base64_decode($request->bagian);   

            $cekData = JurnalBagian::where([
                ['jurnal_no','=',$nomer],
                ['jurnal_bagian','=',$bagian],
                ['kode_transaksi','=','TT'],
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
                    
                    DB::beginTransaction();
                    try {
                        $insert = JurnalBagian::create([
                            "jurnal_no"=> $jurnalNo,
                            "jurnal_keterangan"=> $request->keterangan,
                            "jurnal_tanggal"=> date('Y-m-d', strtotime($request->tgl)),
                            "jurnal_bagian"=> $bagian, 
                            "kode_transaksi"=> "TT",                                       
                            "id_kelas"=> Session::get('kelas'),                        
                            "dt_record"=> date("Y-m-d H:i:s"),
                            "user_record"=> Session::get('login_as')
                        ]);
        
                        if($insert) {
        
                            $nominal = $request->nominal;
                            $find = [","];
                            $replace = [""];
                            $newNominal = str_replace($find, $replace, $nominal);
                            $idTransaksi2 = ($request->id_transaksi == 1) ? 2:1;                    
                                                
                            $insertDet = JurnalBagianDetail::create([
                                "id_perkiraan"=> $request->id_perkiraan1,
                                "id_jurnal_bagian"=> $insert->jurnal_bagian_id,
                                "id_jenis_transaksi"=> 1,
                                "jurnal_det_nominal"=> $newNominal,
                                "id_rekening"=> $request->id_rekening2,
                                "id_kode_transaksi"=> $request->id_kode_transaksi,
                                "dt_record"=> date("Y-m-d H:i:s"),
                                "user_record"=> Session::get('login_as'),   
                            ]);

                            // entry kas teller ==> kredit
                            $insertDet = JurnalBagianDetail::create([
                                "id_perkiraan"=> Session::get('1XXKATEL_ID'), // id perkiraan untuk kas teller
                                "id_jurnal_bagian"=> $insert->jurnal_bagian_id,
                                "id_jenis_transaksi"=> 2,
                                "jurnal_det_nominal"=> $newNominal,
                                "id_rekening"=> $request->id_rekening2,
                                "id_kode_transaksi"=> $request->id_kode_transaksi,
                                "dt_record"=> date("Y-m-d H:i:s"),
                                "user_record"=> Session::get('login_as'),   
                            ]);
        
        
                            if($insertDet) {
                                DB::commit();
                                return response()->json([
                                    'status'=>'insert_successful',
                                    'id'=>$insert->jurnal_bagian_id,
                                    'jbNo'=>$jurnalNo,
                                    'jbTgl'=>date('Y-m-d', strtotime($request->tgl)),
                                    'jbNoRekening'=>$request->no_rekening,
                                    "jbKodeTransaksi"=> $request->kode_transaksi,
                                    'jbIdTransaksi'=>1,
                                    'jbNominal'=>$newNominal,
                                    ]);                
                            } else{
                                return response()->json(['status'=>'insert_failed','msg'=>'Insert Failed']);    
                            }
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
                    "jbKodeTransaksi"=> $request->kode_transaksi,
                    'jbIdTransaksi'=>1,
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
            ->leftJoin('m_kode_transaksi as e', 'b.id_kode_transaksi', '=', 'e.id')
            ->select('a.*', 'b.*', 'e.kode_transaksi', 'c.kode_perkiraan','c.nama_perkiraan','d.nomor_rekening')            
            ->where('jurnal_no', '=', $search)            
            ->where('a.kode_transaksi', '=', 'TT')            
            ->where('a.id_kelas', '=', Session::get('kelas'))
			->where('c.id_lembaga', '=', Session::get('idLembaga'))
            ->where('e.jenis_transaksi', '=', '1')
            ->where('jurnal_bagian','=',$bagian)
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
                'jbNoRekening'=> $searchData[0]->nomor_rekening,
                'jbKodeTransaksi'=> $searchData[0]->id_kode_transaksi,
                'jbKodeTransaksi2'=> $searchData[0]->kode_transaksi,
                'data' => $searchData
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }                
    }
}

