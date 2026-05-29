<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\NasabahIndividu;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use App\Models\TRekeningNasabah;
use App\Models\TRekeningPinjaman;
use App\Models\TRekeningAngsuranPinjaman;
use App\Models\NeracaAkhir;
use App\Models\NeracaAkhirDetail;
use Session;
use Auth;

class LapNasabahPinjamanMdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		
        $data = array(
            'title' => 'POSISI SALDO NASABAH PEMBIAYAAN MUDHARABAH',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped'
        );         
                
        //return view('lap_nasabah_pinjaman/index', compact('data'));        
        $returnHTML = view('lap_nasabah_pinjaman_md/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }    

    public function getData(Request $request)
    {
        $date = $request->date;
        $tahun = date('Y', strtotime($date));
        $bulan = date('m', strtotime($date));        
        $awalDate = $tahun."-".$bulan."-01"; 

        // $cekData = NasabahIndividu::where([
        //     ['sa_pin_temp','!=',null]            
        // ])->get();

        // // insert untuk data default master
        // for($a=0; $a<count($cekData); $a++){
        // //for($a=0; $a<1; $a++){            

        //     $kodePerkiraanReguler = "106003"; // pinjaman reguler
        //     $kodePerkiraanInstallment = "106004"; // pinjaman installment
        //     $kodePerkiraanCC = "106005"; // pinjaman kartu kredit
        //     $jenisRekening = 4;            
        //     $idPerkiraan = array("27","28","28","29");
        //     $idPinjaman = array("2","1","1","3");
        //     $kode = array($kodePerkiraanReguler,$kodePerkiraanInstallment,$kodePerkiraanInstallment,$kodePerkiraanCC);
        //     $bagian = "CS";            
        //     // $ke = array("1","1","2","1");

        //     for($b=0; $b<4; $b++){

        //         $cekDataRekening = TRekeningNasabah::where([
        //             ['id_nasabah','=',$cekData[$a]->id],
        //             ['id_kelas','=',Session::get('kelas')],
        //             ['id_pinjaman','=',$idPinjaman[$b]],
        //             ['id_jenis_rekening','=',4]        
        //         ])->get();
                
        //         if(count($cekDataRekening)==0){                    

        //             $saPinTem[$a] = $cekData[$a]->sa_pin_temp;
        //             $saPinTem2[$a] = $cekData[$a]->sa_pin_temp_2;
        //             $saPinCC[$a] = $cekData[$a]->sa_pinkre_temp;                                
        //             $saldoAwalTemx = array($cekData[$a]->sa_pin_temp,$cekData[$a]->sa_pin_temp,$cekData[$a]->sa_pin_temp_2,$cekData[$a]->sa_pinkre_temp);
        //             $saldoAwalTemy = array($cekData[$a]->sa_pin_temp,$cekData[$a]->sa_pin_temp_2);

        //             //untuk 106004 default rekening dibuatkan 2x (untuk pinjaman motor dan kpr)
        //             $loopingInstallment = ($kode[$b] == "106004") ? 2:1;                    

        //             for($e=0, $f=1; $e<$loopingInstallment; $e++, $f++){

        //                 $orderObj = DB::table('t_rekening_nasabah')->select('nomor_rekening')->latest('id')->first();        
        //                 if ($orderObj) {
        //                     $lastKodeNumber[$b][$e] = explode('.',$orderObj->nomor_rekening);
        //                     $lastKodeNumber2[$b][$e] = substr($lastKodeNumber[$b][$e][1],0,4);
        //                     $generate[$b][$e] = ($lastKodeNumber2[$b][$e]);
        //                     $KodeNumber2[$b][$e] = str_pad(($generate[$b][$e] + 1), 4, "0", STR_PAD_LEFT);
                            
        //                 } else {
        //                     $KodeNumber2[$b][$e] = str_pad(1, 4, "0", STR_PAD_LEFT);
        //                 }
                        
        //                 $nomorRekeningReguler[$b][$e] = $kode[$b].'.'.$KodeNumber2[$b][$e].$f;

        //                 $insertRekPin[$b][$e] = TRekeningNasabah::create([
        //                     "nomor_rekening"=> $nomorRekeningReguler[$b][$e],
        //                     "id_perkiraan"=> $idPerkiraan[$b],				
        //                     "id_jenis_rekening"=> $jenisRekening,
        //                     "id_pinjaman"=> $idPinjaman[$b],				
        //                     "id_nasabah"=> $cekData[$a]->id,
        //                     "tanggal_buka"=> date('Y-m-d', strtotime($request->date)), 
        //                     "id_kelas"=> Session::get('kelas'),
        //                     "user_record"=> Auth::user()->name,                              
        //                     "dt_record"=> date("Y-m-d H:i:s")   
        //                 ]);                    

        //                 //insert rekening pinjaman
        //                 if($kode[$b] != "106005") {

        //                     $saldoAwalTem[$b][$e] = ($kode[$b] == "106004") ? $saldoAwalTemy[$e]:$saldoAwalTemx[$b];
        //                     $provisiNominal = ((($cekData[$a]->provisi)/100) * $saldoAwalTem[$b][$e]);                                                        
        //                     $angsuranPerbulan[$b][$e] = ($saldoAwalTem[$b][$e] / $cekData[$a]->jangka_waktu_temp);
        //                     $orderPin = DB::table('t_rekening_pinjaman')->select('bukti_dropping')->where('keterangan','=','Data Saldo Awal')->latest('rekening_pinjaman_id')->first();        
        //                     if ($orderPin) {
        //                         $lastKodeNumberPin[$b][$e] = explode('.',$orderPin->bukti_dropping);
        //                         $lastKodeNumberPin2[$b][$e] = $lastKodeNumberPin[$b][$e][1];
        //                         $KodeNumberPin2[$b][$e] = str_pad($lastKodeNumberPin2[$b][$e] + 1, 5, "0", STR_PAD_LEFT);
                                
        //                     } else {
        //                         $KodeNumberPin2[$b][$e] = str_pad(1, 5, "0", STR_PAD_LEFT);                 
        //                     }

        //                     $buktiDropping[$b][$e] = $bagian.'D.'.$KodeNumberPin2[$b][$e].'.'.date('d-m-y', strtotime($request->tgl));
        //                     $buktiProvisi[$b][$e] = $bagian.'P.'.$KodeNumberPin2[$b][$e].'.'.date('d-m-y', strtotime($request->tgl));
        //                     $idPerkiraanProvisi[$b][$e] = ($kode[$b]=="106003") ? 193 : 194;
        //                     $idPerkiraanDropping[$b][$e] = ($kode[$b]=="106003") ? 27 : 28;
        //                     $jenisPinjaman[$b][$e] = ($kode[$b]=="106003") ? 2 : 1; // 1: installment; 2:reguler

        //                     // Insert di Tabel Pinjaman
        //                     $insertPinjaman[$b][$e] = TRekeningPinjaman::create([
        //                         "jenis_pinjaman"=> $jenisPinjaman[$b][$e],  
        //                         "id_rekening"=> $insertRekPin[$b][$e]->id,
        //                         "tanggal_realisasi"=> date("Y-m-d H:i:s"),
        //                         "jangka_waktu"=> $cekData[$a]->jangka_waktu_temp,
        //                         "nominal_pokok"=> $saldoAwalTem[$b][$e],
        //                         "bunga_efektif_anuitas"=> $cekData[$a]->suku_bunga, 
        //                         "bunga_efektif_bulan"=> $cekData[$a]->irr_temp, 
        //                         "provisi_persen"=> $cekData[$a]->provisi, 
        //                         "provisi_nominal"=> $provisiNominal,
        //                         "angsuran_bulan"=> $angsuranPerbulan[$b][$e], 
        //                         "id_perkiraan_provisi"=> $idPerkiraanProvisi[$b][$e],
        //                         "bukti_provisi"=> $buktiProvisi[$b][$e],
        //                         "id_perkiraan_dropping"=> $idPerkiraan[$b][$e],
        //                         "bukti_dropping"=> $buktiDropping[$b][$e],
        //                         "keterangan"=> "Data Saldo Awal",
        //                         "id_kelas"=> Session::get('kelas'),                        
        //                         "dt_record"=> date("Y-m-d H:i:s"),
        //                         "user_record"=> Auth::user()->name                 
        //                     ]); 
                            
        //                     $jangkaWaktu = ($cekData[$a]->jangka_waktu_temp)+1;
        //                     for($c=0; $c<$jangkaWaktu; $c++){
                                
        //                         // generate angsuran ke
        //                         $orderAngs = DB::table('t_rekening_pinjaman_angsuran')->select('angsuran_ke','tanggal_jth_tempo')->where('id_rekening_pinjaman','=',$insertPinjaman[$b][$e]->rekening_pinjaman_id)->latest('pinjaman_angsuran_id')->first();        
        //                         if ($orderAngs) {
        //                             $KodeNumberAngs2 = str_pad($orderAngs->angsuran_ke + 1, 3, "0", STR_PAD_LEFT);
        //                             $date = $orderAngs->tanggal_jth_tempo;
                                    
        //                         } else {
        //                             $KodeNumberAngs2 = str_pad(0, 3, "0", STR_PAD_LEFT);                 
        //                             $date = date("Y-m-d");
        //                         }                            
                                
        //                         //generate tgl jatuh tempo                            
        //                         $currentMonth = date("m",strtotime($date));
        //                         $nextMonth = date("m",strtotime($date."+1 month"));

        //                         if($currentMonth==$nextMonth-1 && (date("j",strtotime($date)) != date("t",strtotime($date)))){
        //                             $nextDate = date('Y-m-d',strtotime($date." +1 month"));
        //                         }else{
        //                             $nextDate = (date('d') > 28) ? date('Y-m-d', strtotime("last day of next month",strtotime($date))) : date('Y-m-d', strtotime("next month",strtotime($date)));
        //                         }
                                
        //                         $nominalPokok = $saldoAwalTem[$b][$e];
        //                         $sukuBunga = ($cekData[$a]->suku_bunga)/100;
        //                         $provisiBunga = ($cekData[$a]->provisi)/100;  
        //                         $IRRBunga = ($cekData[$a]->irr_temp)/100;  
        //                         $tagihanBunga = $nominalPokok*($sukuBunga/12);                            
        //                         $provisiNominal = $provisiBunga * $nominalPokok;                            
        //                         $estimasiAwal = $provisiNominal - $nominalPokok;                                                            
        //                         $saldoAkhirAwal = $nominalPokok - $provisiNominal;                            
                                
        //                         if($c==0){
        //                             $saldoAwalx = 0;
        //                         } else if($c==1) {
        //                             $saldoAwalx = $saldoAkhirAwal;
        //                         } else {
        //                             $saldoAwalx = $saldoAkhir[$c-1];
        //                         }

        //                         $IRRNominal = $saldoAwalx * $IRRBunga;
        //                         $amortisasi = $IRRNominal - $tagihanBunga;

        //                         if(($jenisPinjaman[$b][$e])==1){                                                                
        //                             $angsuranPokok = $nominalPokok/$cekData[$a]->jangka_waktu_temp;
        //                         } else {
        //                             $angsuranPokok = (($cekData[$a]->jangka_waktu_temp)==$KodeNumberAngs2) ? $nominalPokok:0;                                                               
        //                         }             

        //                         $estimasi = $angsuranPokok + $tagihanBunga;
        //                         $saldoAkhir[$c] = $saldoAwalx + $IRRNominal - $angsuranPokok - $tagihanBunga;
                                
        //                         $insertAngsuran = TRekeningAngsuranPinjaman::create([
        //                             "id_rekening_pinjaman"=> $insertPinjaman[$b][$e]->rekening_pinjaman_id,
        //                             "id_rekening"=> $insertRekPin[$b][$e]->id,  
        //                             "angsuran_ke"=> $KodeNumberAngs2,
        //                             "tanggal_jth_tempo"=> ($c==0) ? $date:$nextDate,
        //                             "estimasi"=> ($c==0) ? $estimasiAwal:$estimasi,
        //                             "saldo_awal"=> $saldoAwalx,
        //                             "suku_bunga_efektif"=> ($c==0) ? 0:$IRRNominal,
        //                             "angsuran_pokok"=> ($c==0) ? 0:$angsuranPokok,
        //                             "tagihan_bunga"=> ($c==0) ? 0:$tagihanBunga,
        //                             "amortisasi"=> ($c==0) ? 0:$amortisasi,
        //                             "saldo_akhir"=> ($c==0) ? $saldoAkhirAwal:$saldoAkhir[$c],
        //                             "dt_record"=> date("Y-m-d H:i:s"),
        //                             "user_record"=> Auth::user()->name                 
        //                         ]); 
        //                     }

        //                     // Insert Jurnal Bagian
        //                     $jurnalNoCollect = [$buktiDropping[$b][$e],$buktiProvisi[$b][$e]];
        //                     $nominal = [$saldoAwalTem[$b][$e],$provisiNominal];
        //                     $idJenisTransaksi = [1,2];                        
        //                     $idPerkiraanJBDet = array($idPerkiraanDropping[$b][$e],$idPerkiraanProvisi[$b][$e]);                        

        //                     for($d=0; $d<2; $d++){
                
        //                         $insertJB[$d] = JurnalBagian::create([
        //                             "jurnal_no"=> $jurnalNoCollect[$d],
        //                             "jurnal_keterangan"=> "Data Saldo Awal",
        //                             "jurnal_tanggal"=> date('Y-m-d'),
        //                             "jurnal_bagian"=> $bagian,                    
        //                             "id_kelas"=> Session::get('kelas'),                        
        //                             "dt_record"=> date("Y-m-d H:i:s"),
        //                             "user_record"=> Auth::user()->name                 
        //                         ]);

        //                         //Insert Jurnal Bagian Detail (Pinjaman)
        //                         $insertJBDet1 = JurnalBagianDetail::create([
        //                             "id_perkiraan"=> $idPerkiraanJBDet[$d],
        //                             "id_jurnal_bagian"=> $insertJB[$d]->jurnal_bagian_id,
        //                             "id_jenis_transaksi"=> $idJenisTransaksi[$d],
        //                             "jurnal_det_nominal"=> $nominal[$d],
        //                             "id_rekening"=> $insertRekPin[$b][$e]->id,
        //                             "dt_record"=> date("Y-m-d H:i:s"),
        //                             "user_record"=> Auth::user()->name,   
        //                         ]);
                                
        //                     }
        //                 }
        //             }                    
        //         }
        //     }                                            
        // }       

        $dataTable = DB::select(                
            // Belum Termasuk Pinjaman Kartu Kredit
            DB::raw('
                SELECT nama, nomor_rekening, nominal_pokok, bunga_efektif_anuitas, jangka_waktu, nominal_efektif_anuitas,
                debit_angsuran, kredit_angsuran, /*debit_suku_bunga, kredit_suku_bunga,*/ df_trans_perkiraan
                FROM t_rekening_pinjaman as a 
                LEFT JOIN t_rekening_nasabah as b on a.id_rekening = b.id
                LEFT JOIN m_nasabah as c on b.id_nasabah = c.id
                LEFT JOIN m_perkiraan as j on b.id_perkiraan = j.id
                LEFT JOIN 
                    (
                        select d.id_rekening,
                        sum(CASE when d.id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit_angsuran,
                        sum(CASE when d.id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit_angsuran
                        from t_jurnal_bagian_detail d
                        left join t_jurnal_bagian e on d.id_jurnal_bagian = e.jurnal_bagian_id
                        where d.id_perkiraan in ("'.Session::get('1PBHMUDH_ID').'")
                        and e.jurnal_tanggal <= "'.date('Y-m-d', strtotime($request->date)).'"
                        and e.id_kelas = "'.Session::get('kelas').'"          
                        group by d.id_rekening                      
                    ) f on b.id = f.id_rekening
                /* LEFT JOIN 
                    (
                        select g.id_rekening,
                        sum(CASE when g.id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit_suku_bunga,
                        sum(CASE when g.id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit_suku_bunga
                        from t_jurnal_bagian_detail g
                        left join t_jurnal_bagian h on g.id_jurnal_bagian = h.jurnal_bagian_id
                        where g.id_perkiraan in (193,194)
                        and h.jurnal_tanggal <= "'.date('Y-m-d', strtotime($request->date)).'"
                        and h.id_kelas = "'.Session::get('kelas').'"     
                        and jurnal_keterangan != "Data Saldo Awal"                           
                        group by g.id_rekening
                    ) i on b.id = i.id_rekening */
                WHERE a.id_kelas = "'.Session::get('kelas').'"
                and id_jenis_rekening = 7     
				and id_lembaga = "'.Session::get('idLembaga').'"				
                and tanggal_buka <= "'.date('Y-m-d', strtotime($request->date)).'"
                GROUP BY nama, nomor_rekening, nominal_pokok, bunga_efektif_anuitas, jangka_waktu, nominal_efektif_anuitas, 
                debit_angsuran, kredit_angsuran, /*debit_suku_bunga, kredit_suku_bunga,*/ df_trans_perkiraan
            ')            
        );                

        if($dataTable) {
            return response()->json([
                'status'=>'oke',
                'data' => $dataTable,
                'total' => count($dataTable),
                'date' => date('Y-m-d', strtotime($request->date))
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }
    }

    // public function getDataHis(Request $request)
    // {
    //     $date = $request->date;
    //     $tahun = date('Y', strtotime($date));
    //     $bulan = date('m', strtotime($date));        
    //     $awalDate = $tahun."-".$bulan."-01";     

    //     $dataTable = DB::select(
    //         DB::raw('
    //             SELECT nama, nomor_rekening, df_trans_perkiraan, a.id,
    //             sum(CASE when c.id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit,
    //             sum(CASE when c.id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit                
    //             FROM t_rekening_nasabah as a 
    //             LEFT JOIN m_nasabah as b on a.id_nasabah = b.id                 
    //             LEFT JOIN t_jurnal_bagian_detail c on c.id_rekening = a.id
    //             LEFT JOIN m_perkiraan as d on a.id_perkiraan = d.id
    //             LEFT JOIN t_jurnal_bagian as e on c.id_jurnal_bagian = e.jurnal_bagian_id
    //             WHERE a.id_kelas = "'.Session::get('kelas').'"
    //             and id_jenis_rekening = 2
    //             and c.id_perkiraan = 74
    //             and jurnal_tanggal <= "'.date('Y-m-d', strtotime($date)).'"                
    //             GROUP BY nama, nomor_rekening, df_trans_perkiraan, a.id
    //         ')
    //     );        

    //     if($dataTable) {
    //         return response()->json([
    //             'status'=>'oke',
    //             'data' => $dataTable,
    //             'total' => count($dataTable),
    //             'date' => $date
    //             ]);
    //     } else {
    //         return response()->json(['status'=>'failed']);
    //     }
    // }
    
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
}
