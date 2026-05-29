<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\EditPerkiraan;
use App\Models\TRekeningNasabah;
use App\Models\TRekeningAngsuranPinjaman;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use Session;
use Auth;

class AngsuranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index($kode)
    {		
        $LEditPerkiraan = EditPerkiraan::where('kode_perkiraan','like','300%')->get();
        $LNasabahIndividu = DB::table('t_rekening_pinjaman as a')
            ->leftJoin('t_rekening_nasabah as b', 'a.id_rekening', '=', 'b.id')            
            ->leftJoin('m_nasabah as c', 'b.id_nasabah', '=', 'c.id')            
            ->select('a.rekening_pinjaman_id', 'b.nomor_rekening', 'c.nama')
            ->where('a.id_kelas','=',Session::get('kelas'))  
            ->whereIn('b.id_jenis_rekening', [4,7]) 
            ->orderBy('b.id_jenis_rekening', 'asc')  
            ->orderBy('b.nomor_rekening', 'asc')       
            ->get();        

        $data = array(
            'title' => 'Input Angsuran Pembiayaan',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
            'kode' => $kode            
        );         
                        
        // return view('angsuran/index', compact('data','LEditPerkiraan','LNasabahIndividu'));        
        $returnHTML = view('angsuran/index',compact('data','LEditPerkiraan','LNasabahIndividu'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    
    
    public function getDataAngsuranKe(Request $request, $id)
    {           
        $angsuranKe = DB::table('t_rekening_pinjaman_angsuran')
                ->where('id_rekening_pinjaman', '=', $request->idRekPin)
                ->where('angsuran_ke', '=', $request->ke)
                ->get();
		
		if(isset($angsuranKe)){
			$html_users = '<select class="form-select" id="user_kelas" aria-label="Default select example">';
			foreach($angsuranKe as $data_angsuran){
			 $html_users .= '<option value='.$data_angsuran->name.'>'.$data_angsuran->name.'</option>';
			}
			$html_users .= '</select>';
		}else{
			$html_users = 'Kelas Belum Disetup';
		}

		return response()->json([
            'status'=>'successful',
            'data_users' => $html_users
        ]);
    }

    public function getDataAngsuran(Request $request, $id)
    {           
        $LNasabahIndividu = DB::table('t_rekening_pinjaman_angsuran as a')
        ->leftJoin('t_rekening_nasabah as b', 'a.id_rekening', '=', 'b.id') 
        ->select('a.*', 'b.nomor_rekening',)     
        ->where('id_rekening_pinjaman','=',$id)    
        ->where('angsuran_ke','!=',"0") 
        ->where('a.status','=',"n") 
        ->orderby('angsuran_ke','asc')
        ->limit(1)
        ->get();    

        $pecah = explode("-",$LNasabahIndividu[0]->nomor_rekening);
        //$ke = $pecah[2];
        $noRekening = $pecah[0].'.'.$pecah[1];
        $idRekening = $LNasabahIndividu[0]->id_rekening;
		
        if($pecah[0]==Session::get('1XXPMUDH')){
            $idPerkiraanProvisi = "";
            $kodePerkiraanProvisi = "";
            $idPerkiraanPinjaman = Session::get('1XXPMUDH_ID');
            $kodePerkiraanPinjaman = Session::get('1XXPMUDH');
            $jenisPinjaman = 2; // mudharabah

        } else if($pecah[0]==Session::get('1XXPMURA')){
            $idPerkiraanProvisi = Session::get('1XXMMURA_ID'); //id perkiraan margin ditangguhkan
            $kodePerkiraanProvisi = Session::get('1XXMMURA');; // kode perkiraan margin ditangguhkan
            $idPerkiraanPinjaman = Session::get('1XXPMURA_ID');
            $kodePerkiraanPinjaman = Session::get('1XXPMURA');
            $jenisPinjaman = 1; // murabahah

        } else {
            $idPerkiraanProvisi = "";
            $kodePerkiraanProvisi = "";
            $idPerkiraanPinjaman = "";
            $kodePerkiraanPinjaman = "";
            $jenisPinjaman = "";
        }

        if($LNasabahIndividu->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',                                
                'ke' => $LNasabahIndividu[0]->angsuran_ke,
                'angsuranPokok' => $LNasabahIndividu[0]->angsuran_pokok,
                'maginBagiHasil' => $LNasabahIndividu[0]->tagihan_bunga,
                //'amortisasi' => $LNasabahIndividu[0]->amortisasi,                
                'totAngsur' => $LNasabahIndividu[0]->estimasi,
                'idAngsuran' => $LNasabahIndividu[0]->pinjaman_angsuran_id,
                'noRek' => $LNasabahIndividu[0]->nomor_rekening,
                'idPerkiraanProvisi' => $idPerkiraanProvisi,
                'idPerkiraanPinjaman' => $idPerkiraanPinjaman,
                'idRekening' => $idRekening,
				'jenisPinjaman' => $jenisPinjaman,
                ]);            
				}
    }

    public function getIdPerkiraan2(Request $request, $id)
    {   
        $LEditPerkiraan = EditPerkiraan::where('id','=',$id)->get();     
        
        if($LEditPerkiraan->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'kodePerkiraan' => $LEditPerkiraan[0]['kode_perkiraan'],
                'idPerkiraan' => $LEditPerkiraan[0]['id']
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
            
            $jurnalNo = $request->no_bukti;
            
            $bagianGrup = array("JM","JX","AK","CS","PB","JD","TF","JG","LA","AT");
            $bagian = base64_decode($request->bagian);
			$kodePerkiraan = $request->kode_perkiraan; 
			$pattern = substr($kodePerkiraan,-3);

            $cekData = JurnalBagian::where([
                ['jurnal_no','=',$jurnalNo],
                ['jurnal_bagian','=',$bagian],
                ['kode_transaksi','=','AL'],
                ['jurnal_tanggal','=',date('Y-m-d', strtotime($request->tgl))],
                ['id_kelas','=',Session::get('kelas')],
            ])->count();
                        
            if(($cekData)>0){
                return response()->json(['status'=>'insert_failed','msg'=>' Nomer Bukti Sudah Ada, Gunakan Nomer Yang Lain']); 
            
            } elseif($pattern=="000"){
				return response()->json(['status'=>'insert_failed','msg'=>'Tidak Diperbolehkan Menggunakan Kode Perkiraan 000']);
				
			}else if(in_array($bagian, $bagianGrup)){

                DB::beginTransaction();
                try {
                
                    $insert = JurnalBagian::create([
                        "jurnal_no"=> $jurnalNo,
                        "jurnal_keterangan"=> $request->keterangan,
                        "jurnal_tanggal"=> date('Y-m-d', strtotime($request->tgl)),
                        "jurnal_bagian"=> $bagian,    
                        "kode_transaksi"=> "AL",                        
                        "id_kelas"=> Session::get('kelas'),
                        "dt_record"=> date("Y-m-d H:i:s"),
                        "user_record"=> Session::get('login_as')                 
                    ]);

                    $update = TRekeningAngsuranPinjaman::where('pinjaman_angsuran_id', '=', $request->pinjaman_angsuran_id)->update([                              
                        "status"=> "y",      
                        "tanggal_bayar"=> date('Y-m-d', strtotime($request->tgl)),
                        "dt_modified"=> date("Y-m-d H:i:s"),
                        "user_modified"=> Session::get('login_as')
                    ]);  
					
					if(($request->jenis_pinjaman)==1){
						$looping = 4; // murabahah
						$idPerkiraan = [$request->id_perkiraan,Session::get('1XXMMURA_ID'),Session::get('1XXPMURA_ID'),Session::get('1XPMMURA_ID')];
						$idTransaksi = ["1","1","2","2"];
						 $nominal = [
                                str_replace(array(".",",00"),"",$request->pembayaran_angsuran), 
                                str_replace(array(".",",00"),"",$request->bunga_efektif), 
                                str_replace(array(".",",00"),"",$request->pembayaran_angsuran), 
                                str_replace(array(".",",00"),"",$request->bunga_efektif)
                            ]; 
					}else{
						$looping = 2; // mudharabah
						$idPerkiraan = [$request->id_perkiraan, Session::get('1PBHMUDH_ID')];
						$idTransaksi = ["1","2"];
						 $nominal = [
                                str_replace(array(".",",00"),"",$request->pembayaran_angsuran), 
                                str_replace(array(".",",00"),"",$request->pembayaran_angsuran), 
                                
                            ]; 
					}
                    
                    //Insert Jurnal Bagian Detail (Pinjaman)
                    for($a=0; $a<$looping; $a++) {                        

                        $insertJBDet = JurnalBagianDetail::create([
                            "id_perkiraan"=> $idPerkiraan[$a],
                            "id_jurnal_bagian"=> $insert->jurnal_bagian_id,
                            "id_jenis_transaksi"=> $idTransaksi[$a],
                            "jurnal_det_nominal"=> $nominal[$a],
                            "id_rekening"=> $request->id_rekening,                           
                            "dt_record"=> date("Y-m-d H:i:s"),
                            "user_record"=> Session::get('login_as'),   
                        ]);
                    }                                

                    if($insertJBDet) {
                        DB::commit();
                        return response()->json(['status'=>'insert_successful','id'=>$insert->jurnal_bagian_id,'idAngsur'=>$request->pinjaman_angsuran_id]);                
                    } else {
                        return response()->json(['status'=>'insert_failed','msg'=>'Insert Failed']);                
                    }
                } catch (\Throwable $e) {
                    DB::rollback();            
                    throw $e;            
                    return response()->json(['status'=>'insert_failed']);
                }

            } else {
                return response()->json(['status'=>'insert_failed','msg'=>' Akses Ditolak, Silahkan Refresh Halaman'.$bagian]);                
            }

        } else {
            return redirect('asset/');
        }

    }    

    public function destroy(Request $request, $id, $idPin)
    {
        if($request->ajax()){

            $update = TRekeningAngsuranPinjaman::where('pinjaman_angsuran_id', '=', $idPin)->update([                              
                "status"=> "n",                                    
                "tanggal_bayar"=> null,
                "user_modified"=> null
            ]);  
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
        $bagianGrup = array("JM","JX","AK","CS","PB","JD","TF","JG","LA","AT");
        $bagian = base64_decode($request->bagian);
        $search = $request->kode;

        $searchData = DB::table('t_rekening_pinjaman_angsuran as a')
            ->leftJoin('t_jurnal_bagian_detail as b', 'a.id_rekening', '=', 'b.id_rekening')
            ->leftJoin('t_jurnal_bagian as c', 'b.id_jurnal_bagian', '=', 'c.jurnal_bagian_id')
            ->select('a.*', 'b.*', 'c.*')            
            ->where('jurnal_no', '=', $search)
            ->where('id_kelas', '=', Session::get('kelas'))
            ->where('jurnal_bagian','=',$bagian)
            ->where('c.kode_transaksi', '=', 'AL')
            ->where('jurnal_tanggal','=',date('Y-m-d'))
            ->get();

        if(count($searchData)>0) {
            return response()->json([
                'status'=>'oke',
                'jbId'=> $searchData[0]->jurnal_bagian_id,
				'jbKode'=> (($searchData[0]->suku_bunga_efektif)<0) ? "K":"D",
                'jbNo'=> $searchData[0]->jurnal_no,
				'jbIdRekeningPinjaman'=> $searchData[0]->id_rekening_pinjaman,
                'jbKet'=> $searchData[0]->jurnal_keterangan,
                'jbTgl'=> $searchData[0]->jurnal_tanggal,
                'jbBag'=> $searchData[0]->jurnal_bagian,
                'jbAngsuranKe'=> $searchData[0]->angsuran_ke,
				'jbIdRekeningLawan'=> $searchData[0]->id_perkiraan,
                'jbTglBayar'=> $searchData[0]->tanggal_bayar,
                'jbAngPokok'=> $searchData[0]->angsuran_pokok,
                'jbBungaEfektif'=> $searchData[0]->suku_bunga_efektif,                
                'jbAmortisasi'=> $searchData[0]->amortisasi,
                'jbPembayaranAng'=> $searchData[0]->estimasi,
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }                
    }

    // public function search(Request $request)
    // {   
    //     $search = $request->search;

    //     if($search == ''){

    //         $LSearchData = DB::table('t_rekening_pinjaman as a')
    //             ->leftJoin('t_rekening_pinjaman_angsuran as c', 'a.rekening_pinjaman_id', '=', 'c.id_rekening_pinjaman')
    //             ->leftJoin('t_rekening_nasabah as b', 'a.id_rekening', '=', 'b.id')                            
    //             ->leftJoin('m_nasabah as d', 'b.id_nasabah', '=', 'd.id')                      
    //             ->select('a.*','b.*','d.nama','d.cif','d.alamat_ktp','d.id as id_nasabah')
    //             ->where('a.id_kelas','=',Session::get('kelas'))                  
    //             ->orderBy('nomor_rekening', 'asc')  
    //             ->limit(7)     
    //             ->get();                
                                        
    //     }else{     

    //         $LSearchData = DB::table('t_rekening_pinjaman as a')
    //             ->leftJoin('t_rekening_pinjaman_angsuran as c', 'a.rekening_pinjaman_id', '=', 'c.id_rekening_pinjaman')
    //             ->leftJoin('t_rekening_nasabah as b', 'a.id_rekening', '=', 'b.id')                            
    //             ->leftJoin('m_nasabah as d', 'b.id_nasabah', '=', 'd.id')            
    //             ->select('a.*','b.*','d.nama','d.cif','d.alamat_ktp','d.id as id_nasabah')
    //             ->where('a.id_kelas','=',Session::get('kelas'))  
    //             ->where('nomor_rekening', 'like', $search . '%')                
    //             ->orderBy('nomor_rekening', 'asc')  
    //             ->limit(7)     
    //             ->get();                            
    //     }

    //     $response = array();
    //     if($LSearchData->isEmpty()) {
    //             $response[] = array("value"=>"0","label"=>"Note : Tidak Ada Data");
    //     } else {

    //         foreach($LSearchData as $LSearchData){

    //             $response[] = array(
    //                 "value"=>$LSearchData[0]->rekening_pinjaman_id,
    //                 "label"=>$LSearchData[0]->nomor_rekening.' - '.$LSearchData[0]->nama,
    //                 // 'data_users' => $html_users
    //             );
    //         }
    //     }

    //     return response()->json($response);              
    // }

}
