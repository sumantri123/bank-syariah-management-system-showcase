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

class LapRAAPKNDebitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		                    
        
        $data = array(
            'title' => 'Mutasi Rekening Antara Akunting PKN Debet',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
            
        );         
        
        //return view('lap_raapkn_debet/index', compact('data'));        
        $returnHTML = view('lap_raapkn_debet/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }            
   

    public function getData(Request $request)
    {
        $date = $request->date;
        $kodeBagian = base64_decode($request->kode);                

        $dataTable = DB::table('t_jurnal_bagian_detail as a')
            ->leftJoin('t_jurnal_bagian as b', 'a.id_jurnal_bagian', '=', 'b.jurnal_bagian_id')            
            ->select('a.*', 'b.*') 
            ->where('jurnal_tanggal','=',date('Y-m-d', strtotime($date))) 
            ->where('b.id_kelas', '=', Session::get('kelas'))
            ->where('a.id_perkiraan', '=', Session::get('830RADEB_ID'))
            ->orderBy('a.dt_record', 'asc')
            ->get();                    

        $getJumlahTgl = DB::table('t_jurnal_bagian_detail as a')
            ->leftJoin('t_jurnal_bagian as b', 'a.id_jurnal_bagian', '=', 'b.jurnal_bagian_id')            
            ->select('jurnal_tanggal') 
            ->where('jurnal_tanggal','<',date('Y-m-d', strtotime($date))) 
            ->where('b.id_kelas', '=', Session::get('kelas'))
            ->where('a.id_perkiraan', '=', Session::get('830RADEB_ID'))
            ->orderBy('jurnal_tanggal', 'desc')
            ->limit(1)
            ->get();  
                   
        if(count($getJumlahTgl)>0){

            $cariData = DB::select(                
                
               /*  DB::raw("
                        select a.id, df_trans_perkiraan, id_jenis_transaksi, d.*,
                        (case when a.df_trans_perkiraan != a.id_jenis_transaksi THEN 0-nominal_perkiraan else nominal_perkiraan END) as saldo_awal
                        from m_perkiraan a
                        left join (
                            select id_perkiraan, 
                            sum(case when id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit,
                            sum(case when id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit
                            from t_jurnal_bagian_detail b
                            left join t_jurnal_bagian c on b.id_jurnal_bagian = c.jurnal_bagian_id
                            where jurnal_tanggal < '".$getJumlahTgl[0]->jurnal_tanggal."' 
                            and id_kelas = ".Session::get('kelas')."
                            and id_perkiraan = ".Session::get('830RADEB_ID')."
                            group by id_perkiraan, id_jenis_transaksi
                            order by id_perkiraan, id_jenis_transaksi
                        ) as d on a.id = d.id_perkiraan
                        where id = ".Session::get('830RADEB_ID'))."
						and id_lembaga = ".Session::get('idLembaga')."
                    ") */
					
				DB::raw("
                        select a.id, df_trans_perkiraan, id_jenis_transaksi, d.*, e.*,
                        (case when a.df_trans_perkiraan != a.id_jenis_transaksi THEN 0-nominal_perkiraan else nominal_perkiraan END) as saldo_awal
                        from m_perkiraan a
                        left join (
                            select id_perkiraan, 
                            sum(case when id_jenis_transaksi = 1 THEN jurnal_det_nominal END) as debit,
                            sum(case when id_jenis_transaksi = 2 THEN jurnal_det_nominal END) as kredit
                            from t_jurnal_bagian_detail b
                            left join t_jurnal_bagian c on b.id_jurnal_bagian = c.jurnal_bagian_id
                            where jurnal_tanggal = '".$getJumlahTgl[0]->jurnal_tanggal."' 							
                            and id_kelas = ".Session::get('kelas')."
                            and id_perkiraan = ".Session::get('830RADEB_ID')."
                            group by id_perkiraan
                            order by id_perkiraan
                        ) as d on a.id = d.id_perkiraan
						left join (
							select id_perkiraan, 
							sum(case when id_jenis_transaksi = 1 THEN jurnal_det_nominal ELSE 0 END) as debit_kmrn,
							sum(case when id_jenis_transaksi = 2 THEN jurnal_det_nominal ELSE 0 END) as kredit_kmrn
							from t_jurnal_bagian_detail x
							left join t_jurnal_bagian y on x.id_jurnal_bagian = y.jurnal_bagian_id
							where jurnal_tanggal < '".$getJumlahTgl[0]->jurnal_tanggal."' 
							and id_kelas = ".Session::get('kelas')."
							and id_perkiraan = ".Session::get('830RADEB_ID')."
							group by id_perkiraan
							order by id_perkiraan
						) as e on a.id = e.id_perkiraan
                        where id = ".Session::get('830RADEB_ID')."
						and id_lembaga = ".Session::get('idLembaga')."
                    ")
            );  

            //$saldoAwal = ($cariData[0]->saldo_awal + $cariData[0]->debit - $cariData[0]->kredit);            
			$debitKmrn = ($cariData[0]->debit_kmrn == null) ? 0 :$cariData[0]->debit_kmrn;
			$kreditKmrn = ($cariData[0]->kredit_kmrn == null) ? 0 :$cariData[0]->kredit_kmrn;
			$debit = ($cariData[0]->debit == null) ? 0 :$cariData[0]->debit;
			$kredit = ($cariData[0]->kredit == null) ? 0 :$cariData[0]->kredit;
			
			$SA = ($cariData[0]->saldo_awal + $debitKmrn - $kreditKmrn);
			$saldoAwal = ($SA + $cariData[0]->debit - $cariData[0]->kredit);

        } else {
            
            $LEditPerkiraan = EditPerkiraan::where('kode_perkiraan','=',Session::get('830RADEB'))
							->where('id_lembaga','=',Session::get('idLembaga'))
							->get(); 
            $saldoAwal = $LEditPerkiraan[0]->nominal_perkiraan;
        }

        if($dataTable) {
            return response()->json([
                'status'=>'oke',
                'data' => $dataTable,
                'total' => count($dataTable),
                'saldoAwal' => $saldoAwal,
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
