<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\NeracaAkhir;
use App\Models\NeracaAkhirDetail;
use Session;
use Auth;

class LapLabaRugiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		
        $data = array(
            'title' => 'LAPORAN LABA RUGI',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped'
        );         
                
        //return view('lap_labarugi/index', compact('data'));        
        $returnHTML = view('lap_labarugi/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    

    public function getData(Request $request)
    {
        $date = $request->date;
        $tahun = date('Y', strtotime($date));
        $bulan = date('m', strtotime($date));        
        $awalDate = $tahun."-".$bulan."-01";
		$tgl_pilihan = date('Y-m-d',strtotime($request->date));
		$tanggal_berakhir = date('Y-m-d',strtotime('last day of last month'.$tgl_pilihan));
        // $insert = NeracaAkhir::create([
        //     "tanggal"=> date('Y-m-d', strtotime($date)),
        //     "bulan"=> date('n', strtotime($date)),
        //     "tahun"=> date('Y', strtotime($date)),
        //     "tanggal_yt"=> date('Y-m-d', strtotime($date)),
        //     // "id_kelas"=> "",
        //     "created_at"=> date("Y-m-d H:i:s")            
        // ]);

        // if($insert) {

            /*$dataTable = DB::select(
                DB::raw('
                    SELECT
                    kode_perkiraan, nama_perkiraan, sum(jurnal_det_nominal) as total, saldo_yt,
                    b.id_jenis_transaksi as ket_db, c.df_trans_perkiraan as ket_def_db                    
                    FROM t_jurnal_bagian as a 
                    LEFT JOIN t_jurnal_bagian_detail as b on a.jurnal_bagian_id = b.id_jurnal_bagian
                    LEFT JOIN m_perkiraan as c on b.id_perkiraan = c.id
                    LEFT JOIN 
                        (
                            select saldo_yt, id_perkiraan
                            from t_neraca_akhir x
                            left join t_neraca_akhir_detail y on x.neraca_akhir_id = y.id_neraca_akhir
                            where tanggal = 
                                (
                                    SELECT min(tanggal)
                                    FROM t_neraca_akhir
                                    where bulan = "'.date('n', strtotime($date)).'"
                                    and id_kelas = "'.Session::get('kelas').'"
                                )
                            and id_kelas = "'.Session::get('kelas').'"
                        ) d on c.id = d.id_perkiraan
                    WHERE jurnal_tanggal BETWEEN  "'.$awalDate.'" and "'.date('Y-m-d', strtotime($date)).'"
                    AND (kode_perkiraan like "7%"  or kode_perkiraan like "9%")
                    and id_kelas = "'.Session::get('kelas').'"
                    GROUP BY kode_perkiraan, nama_perkiraan, ket_db, ket_def_db
                    ORDER BY kode_perkiraan, ket_db
                ')
            );*/
			$sql = '
					select a.id, a.kode_perkiraan, a.nama_perkiraan,
					a.df_trans_perkiraan, a.id_jenis_transaksi, a.nominal_perkiraan,
					(case when a.id_jenis_transaksi = 1 THEN nominal_perkiraan else 0 END) as Saldo_Debit,
					(case when a.id_jenis_transaksi = 2 THEN nominal_perkiraan else 0 END) as Saldo_Kredit,
					(case when a.df_trans_perkiraan != a.id_jenis_transaksi THEN 0-nominal_perkiraan else nominal_perkiraan END) as Saldo_awal,
					bulan_kemarin.id_perkiraan, bulan_kemarin.debit as debit_bulan_kemarin, bulan_kemarin.kredit as kredit_bulan_kemarin,
					bulan_ini.id_perkiraan, bulan_ini.debit as debit_bulan_ini, bulan_ini.kredit as kredit_bulan_ini
					from `m_perkiraan` a
					left join 
					(
						select id_perkiraan, df_trans_perkiraan,
						sum(Debit) as Debit,
						sum(Kredit) as Kredit
						from (
							select v_tbl.*, `m_perkiraan`.`df_trans_perkiraan`, kode_perkiraan from (
								select id_perkiraan, 
								sum(case when y.id_jenis_transaksi = 1 THEN jurnal_det_nominal else 0 END) as Debit,
								sum(case when y.id_jenis_transaksi = 2 THEN jurnal_det_nominal else 0 END) as Kredit
								from t_jurnal_bagian x
								left join t_jurnal_bagian_detail y on x.jurnal_bagian_id = y.id_jurnal_bagian
								where jurnal_tanggal <= "'.$tanggal_berakhir.'"
								and jurnal_keterangan <> "Data Saldo Awal"
								and id_kelas = "'.Session::get('kelas').'" and id_perkiraan is not null
								group by id_perkiraan, y.id_jenis_transaksi
								order by id_perkiraan, y.id_jenis_transaksi
							) as v_tbl 
							left join m_perkiraan on v_tbl.id_perkiraan = m_perkiraan.`id`
						) as v_table1
						group by id_perkiraan,df_trans_perkiraan
					) as bulan_kemarin on bulan_kemarin.id_perkiraan = a.id
					left join 
					(
						select id_perkiraan, df_trans_perkiraan,
						sum(Debit) as Debit,
						sum(Kredit) as Kredit
						from (
							select v_tbl.*, `m_perkiraan`.`df_trans_perkiraan`, kode_perkiraan from (
								select id_perkiraan, 
								sum(case when y.id_jenis_transaksi = 1 THEN jurnal_det_nominal else 0 END) as Debit,
								sum(case when y.id_jenis_transaksi = 2 THEN jurnal_det_nominal else 0 END) as Kredit
								from t_jurnal_bagian x
								left join t_jurnal_bagian_detail y on x.jurnal_bagian_id = y.id_jurnal_bagian
								where jurnal_tanggal BETWEEN  "'.$awalDate.'" and "'.date('Y-m-d', strtotime($date)).'"
								and jurnal_keterangan <> "Data Saldo Awal"
								and id_kelas = "'.Session::get('kelas').'" and id_perkiraan is not null
								group by id_perkiraan, y.id_jenis_transaksi
								order by id_perkiraan, y.id_jenis_transaksi
							) as v_tbl 
							left join m_perkiraan on v_tbl.id_perkiraan = m_perkiraan.`id`
						) as v_table1
						group by id_perkiraan,df_trans_perkiraan
					) as bulan_ini on bulan_ini.id_perkiraan = a.id
					where (kode_perkiraan like "4%"  or kode_perkiraan like "5%")
					and a.id_lembaga = "'.Session::get('idLembaga').'"
					and ( bulan_kemarin.debit is not null or bulan_kemarin.kredit is not null or
							bulan_ini.debit is not null or bulan_ini.kredit is not null
					)
				';
			$dataTable = DB::select(
                DB::raw($sql)
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

        // } else {
        //     return response()->json(['status'=>'insert_failed','msg'=>'Insert Failed']);                
        // }

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
