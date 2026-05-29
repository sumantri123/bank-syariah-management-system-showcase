<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\GolonganDebitur;
use App\Models\Ikatan;
use App\Models\JenisAngunan;
use App\Models\LokasiDebitur;
use App\Models\JenisPenggunaan;
use App\Models\Penjamin;
use App\Models\PeriodeBayar;
use App\Models\PinjamanJaminan;
use App\Models\SektorEkonomi;
use App\Models\SifatKredit;
use App\Models\SumberDana;
use App\Models\TRekeningNasabah;
use App\Models\TRekeningPinjaman;
use App\Models\TRekeningAngsuranPinjaman;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use App\Models\EditPerkiraan;
use Session;
use Auth;

class KreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index($kode)
    {		
        $LGolonganDebitur = GolonganDebitur::get();
        $LIkatan = Ikatan::get();
        $LJenisPenggunaan = JenisPenggunaan::get();
        $LJenisAngunan = JenisAngunan::get();
        $LLokasiDebitur = LokasiDebitur::where('id_lembaga', '=', Session::get('idLembaga'))->get();
        $LPenjamin = Penjamin::get();
        $LSektorEkonomi = SektorEkonomi::get();
        $LSifatKredit = SifatKredit::get();
        $LPeriodeBayar = PeriodeBayar::get();
        $LSumberDana = SumberDana::where("status","=","2")->get();
        $LEditPerkiraan = EditPerkiraan::where('kode_perkiraan','like','300%')
						->where('id_lembaga', '=', Session::get('idLembaga'))
						->get(); 
						
        $LNasabahIndividu = DB::table('t_rekening_nasabah as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')            
            ->select('a.id as tab_id', 'a.bunga', 'a.id_perkiraan', 'a.tanggal_buka', 'a.jangka', 'a.jenis_pembayaran', 'a.nomor_rekening', 'a.id_nasabah', 'm_nasabah.*')                             
            ->where('a.id_kelas','=',Session::get('kelas'))  
            ->whereIn('a.id_jenis_rekening', [4,7]) 
            ->orderBy('a.id_jenis_rekening', 'asc')  
            ->orderBy('a.nomor_rekening', 'asc')       
            ->get();

        $data = array(
            'title' => 'Realisasi Pembiayaan',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classFormSelect3' => 'single-select2',
            'classTable' => 'table table-sm table-bordered table-striped',
            'kode' => $kode            
        );         
                        
        // return view(
        //     'transaksi_admin_kredit/index', 
        //     compact('data','LGolonganDebitur','LNasabahIndividu','LEditPerkiraan','LIkatan','LJenisAngunan','LPeriodeBayar','LJenisPenggunaan','LLokasiDebitur','LPenjamin','LSektorEkonomi','LSifatKredit','LSumberDana')
        // );        
        $returnHTML = view('transaksi_admin_kredit/index',
                        compact('data','LGolonganDebitur','LNasabahIndividu','LEditPerkiraan','LIkatan','LJenisAngunan','LPeriodeBayar','LJenisPenggunaan','LLokasiDebitur','LPenjamin','LSektorEkonomi','LSifatKredit','LSumberDana'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    
    

    public function getDataNasabah(Request $request, $id)
    {           
        $LNasabahIndividu = DB::table('t_rekening_nasabah as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')            
            ->select('a.id as tab_id', 'a.bunga', 'a.id_perkiraan', 'a.tanggal_buka', 'a.jangka', 'a.jenis_pembayaran', 'a.id_pinjaman', 'a.nomor_rekening', 'a.id_nasabah', 'm_nasabah.*')
            ->where('a.id_kelas','=',Session::get('kelas'))  
            ->where('a.id','=',$id)  
            ->whereIn('a.id_jenis_rekening', [4,7]) 
            ->orderBy('a.id_jenis_rekening', 'asc')  
            ->orderBy('a.nomor_rekening', 'asc')       
            ->get();
        
        $pecah = explode("-",$LNasabahIndividu[0]->nomor_rekening);
		if(strlen($pecah[1])==5){
			$ke = substr($pecah[1],-1); 			
		} else {
			$ke = substr($pecah[1],-2); 			
		}
        
        $rekeningKode = substr($pecah[1],0,4); 
        $noRekening = $pecah[0].'-'.$rekeningKode;
        
        if($pecah[0]==Session::get('1XXPMURA')){ // murabahah
            $idPerkiraan = Session::get('1XXMMURA_ID'); // id perkiraan margin ditangguhkan
            $kode = Session::get('1XXMMURA'); // kode perkiraan margin ditangguhkan			
            $jenisPinjaman = 1;	// jenis pinjaman murabahah	
			$jenisRekening = 4;	// jenis pinjaman murabahah	
        } else if($pecah[0]==Session::get('1XXPMUDH')){ // mudharabah
            $idPerkiraan = "";
            $kode = "";			
            $jenisPinjaman = 2;	// jenis pinjaman mudharabah		
			$jenisRekening = 7;	// jenis pinjaman murabahah	
        } else {
            $idPerkiraan = "";
            $kode = "";
            $jenisPinjaman = "";			
			$jenisRekening = "";	// jenis pinjaman murabahah	
        }

        if($LNasabahIndividu->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'idPer' => $LNasabahIndividu[0]->id_perkiraan,
                'nama' => $LNasabahIndividu[0]->nama,
                'alamat' => $LNasabahIndividu[0]->alamat_ktp,
                'idNasabah' => $LNasabahIndividu[0]->id_nasabah,
                'noRekening' => $noRekening,
				'jenisRekening' => $jenisRekening,
                'idPerkiraanProvisi' => $idPerkiraan,
                'kodePerkiraanProvisi' => $kode,
                'idPinjaman' => $LNasabahIndividu[0]->id_pinjaman,
                'jenisPinjaman' => $jenisPinjaman,				
                'cif' => $LNasabahIndividu[0]->cif,
                'ke' => $ke,
                ]);            
        }
    }

    public function getDataIkatan(Request $request, $id)
    {           

        $LIkatan = Ikatan::where('id','=',$id)->get();        

        if($LIkatan->isEmpty()) {
            return response()->json(['status'=>'null']);            
        } else {
            return response()->json([
                'status'=>'oke',
                'persentase' => $LIkatan[0]->persen,                
                ]);            
        }

    }

    public function getIdPerkiraan2(Request $request, $id)
    {   
        $LEditPerkiraan = EditPerkiraan::where('id','=',$id)
						->where('id_lembaga', '=', Session::get('idLembaga'))
						->get();     
        
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
            
            $bagianGrup = array("JM","JX","AK","CS","PB","JD","TF","JG","LA","AT");
            $bagian = base64_decode($request->bagian);

            $buktiDroping = $request->bukti_droping; 
            //$buktiProvisi = $request->bukti_provisi;

            // Cek Jurnal Nomer         
            $cekData = DB::table('t_jurnal_bagian as a')                  
                ->select('a.*')
                ->where('a.id_kelas','=',Session::get('kelas'))  
                ->where('a.jurnal_bagian','=',$bagian)  
                ->where('a.kode_transaksi','=','RP')  
                ->where('a.jurnal_tanggal','=',date('Y-m-d', strtotime($request->tgl)))  
                ->where('a.jurnal_no','=',$buktiDroping)             
                //->orWhere('a.jurnal_no','=',$buktiProvisi)             
                ->count();

            if(($cekData)>0){
                return response()->json(['status'=>'insert_failed','msg'=>' Nomer Bukti Sudah Ada, Gunakan Nomer Yang Lain']); 
            
            /* } else if($request->bukti_droping === $request->bukti_provisi) {
                return response()->json(['status'=>'insert_failed','msg'=>' Bukti Droping dan Provisi Tidak Boleh Sama']);  */

            }else if(in_array($bagian, $bagianGrup)) {
                                  
                $kodePerkiraan = $request->kode_perkiraan;                
                $pattern = substr($kodePerkiraan,-3); 

                if($pattern=="000"){

                    return response()->json(['status'=>'insert_failed','msg'=>'Tidak Diperbolehkan Menggunakan Kode Perkiraan 000']);
                    
                } else {

                    // Cek Apakah Sudah Pernah Direalisasi
                    $cekTfRekening = DB::table('t_jurnal_bagian_detail as a')   
                    ->leftJoin('t_rekening_nasabah as b', 'a.id_rekening', '=', 'b.id')                           
                    ->select('a.*')
                    ->where('b.id_kelas','=',Session::get('kelas'))  
                    ->where('b.nomor_rekening','=',$request->no_rekening.$request->ke)                                 
                    ->count();       

                    if($cekTfRekening==0){

                        DB::beginTransaction();

                        try {

                            //Cek Rekening 
                            $cekRekening = DB::table('t_rekening_nasabah as a')                       
											->select('a.*')
											->where('a.id_kelas','=',Session::get('kelas'))  
											->where('a.nomor_rekening','=',$request->no_rekening.$request->ke)                                 
											->count();
                            
                            if($cekRekening==0){
                                //Insert Rekening Tabungan
                                $insertRekta = TRekeningNasabah::create([
                                    "nomor_rekening"=> $request->no_rekening.$request->ke,
                                    "id_perkiraan"=> $request->id_perkiraan1,				
                                    "id_jenis_rekening"=> $request->jenis_rekening,				
                                    "id_pinjaman"=> $request->id_pinjaman,
                                    "id_nasabah"=> $request->idNasabah,
                                    "tanggal_buka"=> date('Y-m-d'),                     
                                    "user_record"=> Session::get('login_as'), 
                                    "id_kelas"=> Session::get('kelas'),                             
                                    "dt_record"=> date("Y-m-d H:i:s")   
                                ]);

                                $idRekening = $insertRekta->id;
                            } else {
                                $idRekening = $request->id_rekening2;                                
                            }                               

                            // Insert di Tabel Pinjaman
                            $insertPinjaman = TRekeningPinjaman::create([
                                "jenis_pinjaman"=> $request->jenis_pinjaman,  
                                "id_rekening"=> $idRekening,                          
                                "id_gol_debitur"=> $request->id_golongan_debitur,                    
                                "id_sifat_kredit"=> $request->id_sifat_kredit,                  
                                "id_jenis_penggunaan"=> $request->id_jenis_penggunaan,
                                "id_sektor_ekonomi"=> $request->id_sektor_ekonomi,                  
                                "id_keterkaitan"=> $request->id_keterkaitan,               
                                "id_sumber_dana"=> $request->id_sumber_dana,                   
                                "id_periode_bayar"=> $request->id_periode_bayar,                     
                                "id_lokasi_debitur"=> $request->id_lokasi_debitur,                   
                                "id_penjamin"=> $request->id_penjamin,                    
                                "id_jenis_usaha"=> $request->id_jenis_usaha,                     
                                "id_jenis_anggunan"=> $request->id_jenis_angunan,                   
                                "id_ikatan"=> $request->id_ikatan,                                                 
                                "ikatan_persen"=> $request->persentase_ikatan,                   
                                "bagian_dijamin"=> $request->bagian_dijamin,  
                                "nilai_agunan"=> !strlen($request->nilai_angunan) ? null : str_replace(array(".",",00"),"",$request->nilai_angunan), 
                                "taksasi_agunan"=> !strlen($request->taksasi_agunan) ? null : str_replace(array(".",",00"),"",$request->taksasi_agunan),
                                "tanggal_realisasi"=> $request->tgl, 
                                "angka_realisasi"=> "",
                                "jangka_waktu"=> $request->jangka_waktu, 
                                "nominal_pokok"=> str_replace(array(".",",00"),"",$request->pokok_plafon), 
                                "nomor_pk"=> $request->nomer_pk,                             
                                "id_perkiraan_dropping"=> $request->id_perkiraan1,                                
                                "bukti_dropping"=> $request->bukti_droping, 
                                "kode_ao"=> $request->kode_ao,                             
                                "id_kelas"=> Session::get('kelas'),                        
                                "dt_record"=> date("Y-m-d H:i:s"),
                                "user_record"=> Session::get('login_as'), 
								"id_perkiraan_lawan"=> (($request->jenis_pinjaman) == 2) ? $request->rek_lawan_perk : null,
								"nominal_efektif_anuitas"=> (($request->jenis_pinjaman) == 1) ? str_replace(array(".",",00"),"",$request->bunga_nom_per_pa):null, 
								"angsuran_bulan"=>  (($request->jenis_pinjaman) == 1) ? str_replace(array(".",",00"),"",$request->angsuran_per_bulan):null,
								"id_perkiraan_provisi"=> (($request->jenis_pinjaman) == 1) ? $request->id_perkiraan_provisi:null, //id perkiraan margin murabahah								
                            ]);   


                            // Insert Jaminan Pinjaman
                            if(count($request->jaminan) > 0){
                                for($c=0; $c < count($request->jaminan); $c++){

                                    if($request->jaminan[$c] !=""){
                                        $insertJaminan = PinjamanJaminan::create([
                                            "id_rekening_pinjaman"=> $insertPinjaman->rekening_pinjaman_id,
                                            "nama_jaminan"=> $request->jaminan[$c],                                                                                          
                                            "dt_record"=> date("Y-m-d H:i:s"),
                                            "user_record"=> Session::get('login_as')
                                        ]);   
                                    }
                                }
                            }  

                            // Insert Tabel Angsuran
                            $rekeningPinjamanId = $insertPinjaman->rekening_pinjaman_id;
                            $jangkaWaktu = ($request->jangka_waktu)+1;
                            for($b=0; $b<$jangkaWaktu; $b++){

                                // generate angsuran ke
                                $order = DB::table('t_rekening_pinjaman_angsuran')->select('angsuran_ke','tanggal_jth_tempo')->where('id_rekening_pinjaman','=',$insertPinjaman->rekening_pinjaman_id)->latest('pinjaman_angsuran_id')->first();        
                                if ($order) {
                                    $KodeNumber2 = str_pad($order->angsuran_ke + 1, 3, "0", STR_PAD_LEFT);
                                    $date = $order->tanggal_jth_tempo;
                                    
                                } else {
                                    $KodeNumber2 = str_pad(0, 3, "0", STR_PAD_LEFT);                 
                                    $date = $request->tgl;
                                }                            
                                
                                //generate tgl jatuh tempo                            
                                $currentMonth = date("m",strtotime($date));
                                $nextMonth = date("m",strtotime($date."+1 month"));

                                if($currentMonth==$nextMonth-1 && (date("j",strtotime($date)) != date("t",strtotime($date)))){
                                    $nextDate = date('Y-m-d',strtotime($date." +1 month"));
                                }else{
                                    $nextDate = (date('d') > 28) ? date('Y-m-d', strtotime("last day of next month",strtotime($date))) : date('Y-m-d', strtotime("next month",strtotime($date)));
                                }

								$saldoAwalTem = str_replace(array(".",",00"),"",$request->pokok_plafon);
								$nominalMarginMurabahah = (($request->jenis_pinjaman) == 1) ? str_replace(array(".",",00"),"",$request->bunga_nom_per_pa):0;
								$nominalPokok = ($saldoAwalTem + $nominalMarginMurabahah);
								//$sukuBunga = ($data[$a]->suku_bunga)/100;						
								$estimasiAwal = 0 - $saldoAwalTem;                                                            
								$saldoAkhirAwal = $saldoAwalTem;  
						
                                if($b==0){
                                    $saldoAwal = 0;
									$totalAngsuranPokok = 0;
									$totalTagihanBunga = 0;
                                } else if($b==1) {
                                    $saldoAwal = $saldoAkhirAwal;
                                } else {
                                    $saldoAwal = $saldoAkhir[$b-1];
                                }
								
								if($b!=$request->jangka_waktu){
									$angsuranPokok = ($b==0) ? 0: round(($saldoAwalTem-$nominalMarginMurabahah)/$request->jangka_waktu,0);
									$tagihanBunga = ($b==0) ? 0: round($nominalMarginMurabahah/$request->jangka_waktu,0); //nominalMarginDitangguhkan                        
									$totalAngsuranPokok += $angsuranPokok; 
									$totalTagihanBunga += $tagihanBunga; 
									
								} else {
									$angsuranPokok = ($b==0) ? 0: $saldoAwalTem -$nominalMarginMurabahah - $totalAngsuranPokok;
									$tagihanBunga = ($b==0) ? 0: $nominalMarginMurabahah - $totalTagihanBunga;
								}		
						
                                $estimasi = $angsuranPokok + $tagihanBunga;
                                $saldoAkhir[$b] = $saldoAwal - $angsuranPokok - $tagihanBunga;
								$estimasiPinjaman = ($b==0) ? $estimasiAwal:$estimasi;
                                
                                $insertAngsuran = TRekeningAngsuranPinjaman::create([
                                    "id_rekening_pinjaman"=> $rekeningPinjamanId,
                                    "id_rekening"=> $idRekening,  
                                    "angsuran_ke"=> $KodeNumber2,
                                    "tanggal_jth_tempo"=> ($b==0) ? $date:$nextDate,                                    
									"estimasi"=> (($request->jenis_pinjaman) == 1) ? $estimasiPinjaman:null,
                                    "saldo_awal"=> $saldoAwal,                                    
                                    "angsuran_pokok"=> (($request->jenis_pinjaman) == 1) ? $angsuranPokok:null,
                                    "tagihan_bunga"=> (($request->jenis_pinjaman) == 1) ? $tagihanBunga:null,                                    
                                    "saldo_akhir"=> ($b==0) ? $saldoAkhirAwal:$saldoAkhir[$b],
                                    "dt_record"=> date("Y-m-d H:i:s"),
                                    "user_record"=> Session::get('login_as')
                                ]); 
                            }
                            
                            $jurnalNoCollect = $request->bukti_droping;
                            $jurnalKeterangan = "Realisasi ".$request->no_rekening.'.'.$request->ke.' '.$request->nama;
							$pembiayaan = $nominalPokok + $tagihanBunga;
                            $nominal = (($request->jenis_pinjaman) == 1) ? [$pembiayaan,$nominalPokok,$tagihanBunga] : [$saldoAwalTem,$saldoAwalTem];
							$idPerkiraanJBDet = (($request->jenis_pinjaman) == 1) ? array(Session::get('1XXPMURA_ID'),Session::get('1XXPSEDI_ID'),Session::get('1XXMMURA_ID')) : array($request->id_perkiraan1,Session::get('3XXRPTAB_ID'));; 
							$idJenisTransaksi = (($request->jenis_pinjaman) == 1) ? [1,2,2] : [1,2];  						
							$looping = (($request->jenis_pinjaman) == 1) ? 3:2;
							
							// Insert Jurnal Bagian
							$insertJB = JurnalBagian::create([
								"jurnal_no"=> $jurnalNoCollect,
								"jurnal_tanggal"=> date('Y-m-d'),                            
								"jurnal_bagian"=> $bagian,  
								"kode_transaksi"=> "RP",                   
								"jurnal_keterangan"=> $jurnalKeterangan,                    
								"id_kelas"=> Session::get('kelas'),                        
								"dt_record"=> date("Y-m-d H:i:s"),
								"user_record"=> Session::get('login_as')
							]);    
								                            
                            for($d=0; $d<$looping; $d++){
                                //Insert Jurnal Bagian Detail (Pinjaman)
                                $insertJBDet[$d] = JurnalBagianDetail::create([
                                    "id_perkiraan"=> $idPerkiraanJBDet[$d],
                                    "id_jurnal_bagian"=> $insertJB->jurnal_bagian_id,
                                    "id_jenis_transaksi"=> $idJenisTransaksi[$d],
                                    "jurnal_det_nominal"=> $nominal[$d],
                                    "id_rekening"=> $idRekening,                           
                                    "dt_record"=> date("Y-m-d H:i:s"),
                                    "user_record"=> Session::get('login_as'),   
                                ]);                                                             
                            }                                                      

                            if($insertJBDet) {
                                DB::commit();
                                return response()->json(
                                    [
                                        'status'=>'insert_successful',
                                        'id'=>$insertPinjaman->rekening_pinjaman_id,
                                        'idRek'=>$idRekening,
                                        'idJb0'=>$insertJB->jurnal_bagian_id,                                        
                                    ]);                
                            } else {
                                return response()->json(['status'=>'insert_failed','msg'=>'Insert Failed']);                
                            }
                            
                        } catch (\Throwable $e) {

                            DB::rollback();            
                            throw $e;            
                            return response()->json(['status'=>'insert_failed']);
            
                        }

                    } else {
                        return response()->json(['status'=>'insert_failed','msg'=>' Rekening Sudah Pernah Direalisasi']);                    
                    }
                    
                }
                
            } else {
                return response()->json(['status'=>'insert_failed','msg'=>' Akses Ditolak, Silahkan Refresh Halaman']);                
            }            

        } else {
            return redirect('asset/');
        }

    }
    
    public function destroy(Request $request)
    {
        $LAngsuranPinjaman = TRekeningAngsuranPinjaman::where('id_rekening_pinjaman','=',$request->idRekPin)
                            ->where('status','=','y')
                            ->count();  

        if($LAngsuranPinjaman>0){
            
            return response()->json(['status'=>'delete_failed','msg'=>'Nasabah Mempunyai Angsuran Yg Sdh Dibayar']);    

        }else {

            if($request->ajax()){

                DB::beginTransaction();

                try {

                    $query = TRekeningPinjaman::find($request->idRekPin)->delete();
                    $query2 = JurnalBagian::find($request->idJb0)->delete();                    
                    $query4 = TRekeningNasabah::find($request->idRek)->delete();

                    if($query4) {
                        DB::commit();
                        return response()->json(['status'=>'delete_successful']);
                    } else {
                        return response()->json(['status'=>'delete_failed']);
                    }
                } catch (\Throwable $e) {

                    DB::rollback();    
                    throw $e;
                    return response()->json(['status'=>'insert_failed']);
                }

            } else {
                return response()->json(['status'=>'delete_failed']);
            }           
        }
        
    }
    
    public function search(Request $request)
    {   
        $search = $request->search;

        if($search == ''){

            $LSearchData = DB::table('t_rekening_pinjaman as a')
                ->leftJoin('t_rekening_nasabah as b', 'a.id_rekening', '=', 'b.id')                            
                ->leftJoin('m_nasabah as d', 'b.id_nasabah', '=', 'd.id')                      
                ->select('a.*','b.*','d.nama','d.cif','d.alamat_ktp','d.id as id_nasabah')
                ->where('a.id_kelas','=',Session::get('kelas'))                  
                ->where('tanggal_buka','=',date('Y-m-d'))
                ->orderBy('nomor_rekening', 'asc')  
                ->limit(7)     
                ->get();

            // $LJaminan = DB::table('t_rekening_pinjaman as a')
            //     ->leftJoin('t_rekening_nasabah as b', 'a.id_rekening', '=', 'b.id')                            
            //     ->leftJoin('t_rekening_pinjaman_jaminan as c', 'a.rekening_pinjaman_id', '=', 'c.id_rekening_pinjaman')                                            
            //     ->select('c.*')
            //     ->where('a.id_kelas','=',Session::get('kelas'))                  
            //     ->orderBy('nomor_rekening', 'asc')  
            //     ->limit(7)     
            //     ->get();

            $LJb = DB::select(
                    DB::raw("
                            select c.id_jurnal_bagian 
                            from t_rekening_pinjaman a 
                            left join t_rekening_nasabah b on a.id_rekening = b.id
                            left join t_jurnal_bagian_detail c on a.id_rekening = c.id_rekening  
                            where a.id_kelas = ".Session::get('kelas')."
                            and jurnal_tanggal = '".date('Y-m-d')."'
                            group by c.id_jurnal_bagian
                            order by jurnal_det_id asc                               
                    ")
                );
                                        
        }else{     

            $LSearchData = DB::table('t_rekening_pinjaman as a')
                ->leftJoin('t_rekening_nasabah as b', 'a.id_rekening', '=', 'b.id')
                ->leftJoin('t_rekening_pinjaman as c', 'a.rekening_pinjaman_id', '=', 'b.id')
                ->leftJoin('m_nasabah as d', 'b.id_nasabah', '=', 'd.id')            
                ->select('a.*','b.*','d.nama','d.cif','d.alamat_ktp','d.id as id_nasabah')
                ->where('a.id_kelas','=',Session::get('kelas'))  
                ->where('nomor_rekening', '=', $search)                
                ->where('tanggal_buka','=',date('Y-m-d'))
                ->orderBy('nomor_rekening', 'asc')  
                ->limit(7)     
                ->get();

            // $LJaminan = DB::table('t_rekening_pinjaman as a')
            //     ->leftJoin('t_rekening_nasabah as b', 'a.id_rekening', '=', 'b.id')                            
            //     ->leftJoin('t_rekening_pinjaman_jaminan as c', 'a.rekening_pinjaman_id', '=', 'c.id_rekening_pinjaman')                                            
            //     ->select('c.*')
            //     ->where('a.id_kelas','=',Session::get('kelas'))  
            //     ->where('nomor_rekening', 'like', $search . '%')                
            //     ->orderBy('nomor_rekening', 'asc')  
            //     ->limit(7)     
            //     ->get(); 
                
            $LJb = DB::select(
                DB::raw("
                        select c.id_jurnal_bagian
                        from t_rekening_pinjaman a 
                        left join t_rekening_nasabah b on a.id_rekening = b.id
                        left join t_jurnal_bagian_detail c on a.id_rekening = c.id_rekening
						left join t_jurnal_bagian d on c.id_jurnal_bagian = d.jurnal_bagian_id
                        where nomor_rekening = '".$search."'
                        and a.id_kelas = ".Session::get('kelas')."
                        and jurnal_tanggal = '".date('Y-m-d')."'
                        group by c.id_jurnal_bagian
                                                     
                ")
            );
            
			
        }

        $response = array();
        if($LSearchData->isEmpty() ) {
                $response[] = array("status"=>"failed","value"=>"0","label"=>"Note : Tidak Ada Data");
        } else {
			/* if($LJb->isEmpty()){
				$response[] = array("status"=>"failed","value"=>"0","label"=>"Note : Tidak Ada Data");
			}else{ */
				foreach($LSearchData as $LSearchData){
					$response[] = array(
						"status"=>"oke",
						"value"=>$LSearchData->rekening_pinjaman_id,
						"label"=>$LSearchData->nomor_rekening.' - '.$LSearchData->nama,
						"data"=>$LSearchData,
						// "jaminan"=>$LJaminan,
						"jb"=>$LJb
					);
				}
			/* } */
        }

        return response()->json($response);              
    }
}
