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

class LapMutasiNasabahDepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		                    
        
        $data = array(
            'title' => 'Saldo Mutasi Deposito',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
            
        );         
        
        //return view('lap_mutasi_nasabah_dep/index', compact('data'));        
        $returnHTML = view('lap_mutasi_nasabah_dep/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }            
   

    public function getData(Request $request)
    {
        $date = $request->date;
        //$kodeBagian = base64_decode($request->kode);                

        // $dataTable = DB::select(                
                
        //     DB::raw("
        //             select nomor_rekening, nama, a.jurnal_det_nominal as saldo_awal, d.*,  l.*
        //             from t_jurnal_bagian_detail a                    
        //             left join t_rekening_nasabah f on a.id_rekening = f.id
        //             left join m_nasabah g on f.id_nasabah = g.id
        //             left join t_jurnal_bagian h on a.id_jurnal_bagian = h.jurnal_bagian_id
        //             left join m_perkiraan i on a.id_perkiraan = i.id
        //             right join (
        //                 select id_perkiraan, id_rekening,
        //                 sum(case when id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit,
        //                 sum(case when id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit
        //                 from t_jurnal_bagian_detail b
        //                 left join t_jurnal_bagian c on b.id_jurnal_bagian = c.jurnal_bagian_id
        //                 where jurnal_tanggal = '".date('Y-m-d', strtotime($date))."'
        //                 and id_kelas = ".Session::get('kelas')."
        //                 and id_perkiraan = 76
        //                 and jurnal_keterangan != 'Data Saldo Awal'
        //                 group by id_perkiraan, id_rekening
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
        //                 and id_perkiraan = 76                        
        //                 group by id_perkiraan, id_rekening
        //                 order by id_perkiraan
        //             ) as l on d.id_rekening = l.id_rekening                 
        //             where a.id_perkiraan = 76                   
        //             and h.id_kelas = ".Session::get('kelas')."
        //             and jurnal_keterangan != 'Data Saldo Awal'
        //             group by a.jurnal_det_nominal, nomor_rekening, nama, a.id_jenis_transaksi, d.id_perkiraan, 
        //             d.id_rekening, d.debit, d.kredit, l.id_perkiraan, l.id_rekening, debit_kemarin, kredit_kemarin
        //         ")
        // );
        
        $dataTable = DB::select(                
                
            DB::raw("
                    select nomor_rekening, nama, d.*, 
                    (case when a.id_jenis_transaksi = 1 THEN jurnal_det_nominal ELSE 0 END) as saldo_awal
                    from t_jurnal_bagian_detail a                    
                    left join t_rekening_nasabah f on a.id_rekening = f.id
                    left join m_nasabah g on f.id_nasabah = g.id
                    left join t_jurnal_bagian h on a.id_jurnal_bagian = h.jurnal_bagian_id
                    left join m_perkiraan i on a.id_perkiraan = i.id
                    right join (
                        select jurnal_det_id, id_rekening, id_jenis_transaksi,
                        sum(case when b.id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit,
                        sum(case when b.id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit
                        from t_jurnal_bagian_detail b
                        left join t_jurnal_bagian c on b.id_jurnal_bagian = c.jurnal_bagian_id
                        where jurnal_tanggal = '".date('Y-m-d', strtotime($date))."'
                        and id_kelas = ".Session::get('kelas')."
                        and id_perkiraan = ".Session::get('2XXDMUDH_ID')." 
                        and jurnal_keterangan != 'Data Saldo Awal'
                        group by id_perkiraan, id_rekening, id_jenis_transaksi, jurnal_det_id
                        order by id_perkiraan
                    ) as d on a.jurnal_det_id = d.jurnal_det_id and a.id_rekening = d.id_rekening
                    where a.id_perkiraan = ".Session::get('2XXDMUDH_ID')."                   
                    and h.id_kelas = ".Session::get('kelas')."
					and i.id_lembaga = ".Session::get('idLembaga')."
                    and jurnal_keterangan != 'Data Saldo Awal'
                    group by a.jurnal_det_nominal, nomor_rekening, nama, a.id_jenis_transaksi,
                    d.id_rekening, d.debit, d.kredit, d.id_jenis_transaksi, d.jurnal_det_id
                    order by nomor_rekening, jurnal_det_id asc
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
            return response()->json(['status'=>'failed']);
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
