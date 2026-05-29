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

class LapKliringSerahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		
        $data = array(
            'title' => 'LAPORAN HASIL KLIRING DEBET',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped'
        );         
                
        //return view('lap_hasil_kliring_debet/index', compact('data'));        
        $returnHTML = view('lap_hasil_kliring_debet/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }    

    public function getData(Request $request)
    {
        $date = $request->date;                       

        $dataTable = DB::select(
            DB::raw("
                select e.kode_transaksi_kliring, e.nama_transaksi_kliring, d.*
                from t_jurnal_kliring as a 
                left join m_sandi_transaksi e on a.id_sandi_transaksi = e.id                            
                left join (
                    select id_perkiraan, jurnal_bagian_id, id_jenis_transaksi,
                    sum(case when id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit,
                    sum(case when id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit
                    from t_jurnal_bagian_detail b
                    left join t_jurnal_bagian c on b.id_jurnal_bagian = c.jurnal_bagian_id
                    where jurnal_tanggal <= '".date('Y-m-d', strtotime($date))."'
                    and id_kelas = ".Session::get('kelas')."
                    and id_perkiraan in ('".Session::get('830RAKRE_ID')."','".Session::get('830RADEB_ID')."')
                    group by id_perkiraan, jurnal_bagian_id, id_jenis_transaksi
                    order by id_perkiraan
                ) as d on a.id_jurnal_bagian = d.jurnal_bagian_id
                where jurnal_kliring_tanggal = '".date('Y-m-d', strtotime($date))."'
                and id_kelas = ".Session::get('kelas')."
                and e.keterangan = 1
                order by id_jenis_transaksi asc
            ")
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
