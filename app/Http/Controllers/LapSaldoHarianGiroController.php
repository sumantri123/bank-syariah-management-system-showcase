<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\EditPerkiraan;
use App\Models\TRekeningNasabah;
use App\Models\JurnalBagian;
use App\Models\JurnalBagianDetail;
use Auth;
use Session;

class LapSaldoHarianGiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		                    
        $LNasabahIndividu = DB::table('t_rekening_nasabah as a')
            ->leftJoin('m_nasabah', 'a.id_nasabah', '=', 'm_nasabah.id')            
            ->select('a.id as tab_id', 'a.bunga', 'a.id_perkiraan', 'a.tanggal_buka', 'a.jangka', 'a.jenis_pembayaran', 'a.nomor_rekening', 'a.id_nasabah', 'm_nasabah.*')                             
            ->where('a.id_kelas','=',Session::get('kelas'))  
            ->where('a.id_jenis_rekening','=', 1) 
            ->orderBy('a.id_jenis_rekening', 'asc')  
            ->orderBy('a.nomor_rekening', 'asc')       
            ->get();

        $data = array(
            'title' => 'SALDO HARIAN NASABAH GIRO RUPIAH',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
            
        );         
        
        //return view('lap_saldo_harian_giro/index', compact('data','LNasabahIndividu'));        
        $returnHTML = view('lap_saldo_harian_giro/index',compact('data','LNasabahIndividu'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }            

    public function getData(Request $request)
    {
        $date = $request->date;
        $idRek = $request->idRek;        

        //kodingan ini digunakan jika listing laporan dilakukan harian
        // $dataTable = DB::select(                
                
        //     DB::raw("
        //             select jurnal_tanggal, nomor_rekening, nama, 
        //             a.jurnal_det_nominal as saldo_awal, a.id_jenis_transaksi, d.*,  l.*
        //             from t_jurnal_bagian_detail a                    
        //             left join t_rekening_nasabah f on a.id_rekening = f.id
        //             left join m_nasabah g on f.id_nasabah = g.id
        //             left join t_jurnal_bagian h on a.id_jurnal_bagian = h.jurnal_bagian_id
        //             left join m_perkiraan i on a.id_perkiraan = i.id
        //             right join (
        //                 select id_perkiraan, id_rekening, c.jurnal_keterangan,
        //                 sum(case when id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit,
        //                 sum(case when id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit
        //                 from t_jurnal_bagian_detail b
        //                 left join t_jurnal_bagian c on b.id_jurnal_bagian = c.jurnal_bagian_id
        //                 where jurnal_tanggal = '".date('Y-m-d', strtotime($date))."'
        //                 and id_kelas = ".Session::get('kelas')."
        //                 and id_perkiraan = 71
        //                 and id_rekening = ".$idRek."
        //                 and jurnal_keterangan != 'Data Saldo Awal'
        //                 group by id_perkiraan, id_kode_transaksi, id_rekening, jurnal_keterangan
        //                 order by id_perkiraan
        //             ) as d on a.id_perkiraan = d.id_perkiraan and a.id_rekening = d.id_rekening  
        //             left join (
        //                 select id_perkiraan, id_rekening,
        //                 sum(case when id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit_kemarin,
        //                 sum(case when id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit_kemarin
        //                 from t_jurnal_bagian_detail j
        //                 left join t_jurnal_bagian k on j.id_jurnal_bagian = k.jurnal_bagian_id
        //                 where jurnal_tanggal <= '".date('Y-m-d', strtotime('-1 day',strtotime($date)))."'
        //                 and id_kelas = ".Session::get('kelas')."
        //                 and id_perkiraan = 71
        //                 and id_rekening = ".$idRek."                     
        //                 group by id_perkiraan, id_rekening
        //                 order by id_perkiraan
        //             ) as l on d.id_rekening = l.id_rekening                 
        //             where a.id_perkiraan = 71                  
        //             and h.id_kelas = ".Session::get('kelas')."
        //             and a.id_rekening = ".$idRek."
        //             and h.jurnal_keterangan = 'Data Saldo Awal'
        //             group by a.jurnal_det_nominal, nomor_rekening, nama, d.jurnal_keterangan, jurnal_tanggal, a.id_jenis_transaksi, d.id_perkiraan, 
        //             d.id_rekening, d.debit, d.kredit, l.id_perkiraan, l.id_rekening, debit_kemarin, kredit_kemarin                   
        //         ")
        // );                    

        $dataTable = DB::select(                
                
            DB::raw("
                    select nomor_rekening, nama, 
                    a.jurnal_det_nominal as saldo_awal, a.id_jenis_transaksi, d.*
                    from t_jurnal_bagian_detail a                    
                    left join t_rekening_nasabah f on a.id_rekening = f.id
                    left join m_nasabah g on f.id_nasabah = g.id
                    left join t_jurnal_bagian h on a.id_jurnal_bagian = h.jurnal_bagian_id
                    left join m_perkiraan i on a.id_perkiraan = i.id
                    right join (
                        select id_perkiraan, id_rekening, c.jurnal_keterangan, jurnal_tanggal,
                        sum(case when id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit,
                        sum(case when id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit
                        from t_jurnal_bagian_detail b
                        left join t_jurnal_bagian c on b.id_jurnal_bagian = c.jurnal_bagian_id
                        where jurnal_tanggal <= '".date('Y-m-d', strtotime($date))."'
                        and id_kelas = ".Session::get('kelas')."
                        and id_perkiraan = ".Session::get('2XXGWADI_ID')." 
                        and id_rekening = ".$idRek." 
                        and jurnal_keterangan != 'Data Saldo Awal'
                        group by id_perkiraan, id_kode_transaksi, id_rekening, jurnal_keterangan, jurnal_tanggal
                        order by jurnal_tanggal
                    ) as d on a.id_perkiraan = d.id_perkiraan and a.id_rekening = d.id_rekening                                   
                    where a.id_perkiraan = ".Session::get('2XXGWADI_ID')."                  
                    and h.id_kelas = ".Session::get('kelas')."
                    and a.id_rekening = ".$idRek." 
					and id_lembaga = ".Session::get('idLembaga')."
                    and h.jurnal_keterangan = 'Data Saldo Awal'
                    group by a.jurnal_det_nominal, nomor_rekening, nama, d.jurnal_keterangan, jurnal_tanggal, a.id_jenis_transaksi, d.id_perkiraan, 
                    d.id_rekening, d.debit, d.kredit
                    order by jurnal_tanggal asc
                ")
        );   

        if($dataTable) {
            return response()->json([
                'status'=>'oke',
                'data' => $dataTable,
                'total' => count($dataTable),                
                'date' => $date
                ]);
        } else {
            return response()->json([
                'status'=>'failed',
                'date'=>$date                
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
}
