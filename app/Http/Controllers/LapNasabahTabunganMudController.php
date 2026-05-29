<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\NasabahIndividu;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use App\Models\TRekeningNasabah;
use App\Models\NeracaAkhir;
use App\Models\NeracaAkhirDetail;
use Session;
use Auth;

class LapNasabahTabunganMudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		
        $data = array(
            'title' => 'POSISI SALDO NASABAH TABUNGAN',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped'
        );         
                
        //return view('lap_nasabah_tabungan/index', compact('data'));        
        $returnHTML = view('lap_nasabah_tabungan_mud/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    

    public function getData(Request $request)
    {
        $date = $request->date;
        $tahun = date('Y', strtotime($date));
        $bulan = date('m', strtotime($date));        
        $awalDate = $tahun."-".$bulan."-01"; 

        // $cekData = NasabahIndividu::where([
        //     ['sa_tab_temp','!=',null]            
        // ])->get();

        // // insert untuk data default master
        // for($a=0; $a<count($cekData); $a++){

        //     $cekDataRekening = TRekeningNasabah::where([
        //         ['id_nasabah','=',$cekData[$a]->id],
        //         ['id_kelas','=',Session::get('kelas')],
        //         ['id_jenis_rekening','=',2]        
        //     ])->get();

        //     if(count($cekDataRekening)==0){

        //         $orderObj = DB::table('t_rekening_nasabah')->select('nomor_rekening')->latest('id')->first();        
        //         if ($orderObj) {
        //             $lastKodeNumber = explode('.',$orderObj->nomor_rekening);
        //             $lastKodeNumber2 = $lastKodeNumber[1];
        //             $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 4, "0", STR_PAD_LEFT);
                    
        //         } else {
        //             $KodeNumber2 = str_pad(1, 4, "0", STR_PAD_LEFT);                 
        //         }
                
        //         $kodePerkiraan = "302001";
        //         $nomorRekening = $kodePerkiraan.'.'.$KodeNumber2;
        //         $jenisRekening = 2;
                
        //         // Insert Rekening Tabungan
        //         $insertRekta = TRekeningNasabah::create([
        //             "nomor_rekening"=> $nomorRekening,
        //             "id_perkiraan"=> 74,				
        //             "id_jenis_rekening"=> $jenisRekening,
        //             "id_nasabah"=> $cekData[$a]->id,
        //             "tanggal_buka"=> date('Y-m-d', strtotime($request->date)), 
        //             "id_kelas"=> Session::get('kelas'),
        //             "user_record"=> Auth::user()->name,                              
        //             "dt_record"=> date("Y-m-d H:i:s")   
        //         ]);

        //         // Insert Jurnal Bagian
        //         $orderJB = DB::table('t_jurnal_bagian')->select('jurnal_no')->where('jurnal_keterangan','=','Data Saldo Awal')->latest('jurnal_bagian_id')->first();        
        //         if ($orderJB) {
        //             $lastKodeNumber = explode('.',$orderJB->jurnal_no);
        //             $lastKodeNumber2 = $lastKodeNumber[1];
        //             $KodeNumber2 = str_pad($lastKodeNumber2 + 1, 5, "0", STR_PAD_LEFT);
                    
        //         } else {
        //             $KodeNumber2 = str_pad(1, 5, "0", STR_PAD_LEFT);                 
        //         }
                
        //         $bagian = "CS";
        //         $jurnalNo = $bagian.'.'.$KodeNumber2.'.'.date('d-m-y', strtotime($request->tgl));

        //         $insertJB = JurnalBagian::create([
        //             "jurnal_no"=> $jurnalNo,
        //             "jurnal_keterangan"=> "Data Saldo Awal",
        //             "jurnal_tanggal"=> date('Y-m-d'),
        //             "jurnal_bagian"=> $bagian,                    
        //             "id_kelas"=> Session::get('kelas'),                        
        //             "dt_record"=> date("Y-m-d H:i:s"),
        //             "user_record"=> Auth::user()->name                 
        //         ]);
                

        //         // Insert Jurnal Bagian Detail
        //         $insertJBDet = JurnalBagianDetail::create([
        //             "id_perkiraan"=> 74,
        //             "id_jurnal_bagian"=> $insertJB->jurnal_bagian_id,
        //             "id_jenis_transaksi"=> 2,
        //             "jurnal_det_nominal"=> $cekData[$a]->sa_tab_temp,
        //             "id_rekening"=> $insertRekta->id,
        //             "id_kode_transaksi"=> 7,
        //             "dt_record"=> date("Y-m-d H:i:s"),
        //             "user_record"=> Auth::user()->name,   
        //         ]);
        //     }
        // }       

        $dataTable = DB::select(
            DB::raw('
                SELECT nama, nomor_rekening, df_trans_perkiraan, a.id,
                sum(CASE when c.id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit,
                sum(CASE when c.id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit                
                FROM t_rekening_nasabah as a 
                LEFT JOIN m_nasabah as b on a.id_nasabah = b.id                 
                LEFT JOIN t_jurnal_bagian_detail c on c.id_rekening = a.id
                LEFT JOIN m_perkiraan as d on a.id_perkiraan = d.id
                LEFT JOIN t_jurnal_bagian as e on c.id_jurnal_bagian = e.jurnal_bagian_id
                WHERE a.id_kelas = "'.Session::get('kelas').'"
                and id_jenis_rekening = 6
				and id_lembaga = "'.Session::get('idLembaga').'"
                and c.id_perkiraan = "'.Session::get('2XXTMUDH_ID').'"
                and jurnal_tanggal <= "'.date('Y-m-d', strtotime($date)).'"                
                GROUP BY nama, nomor_rekening, df_trans_perkiraan, a.id
            ')
            
        );        

        if($dataTable) {
            return response()->json([
                'status'=>'oke',
                'data' => $dataTable,
                'total' => count($dataTable),
                'date' => date('Y-m-d', strtotime($date))
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
