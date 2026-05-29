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

class LapTestKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		
        $data = array(
            'title' => 'DAFTAR VALIDASI TEST KEY',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped'
        );         
                
        //return view('lap_test_key/index', compact('data'));        
        $returnHTML = view('lap_test_key/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }    

    public function getData(Request $request)
    {
        $date = $request->date;               

        $dataTable = DB::select(
            DB::raw('
                SELECT a.*, b.kode_kantor as nama_pengirim, c.kode_kantor as nama_penerima
                FROM t_validasi_test_key as a 
                LEFT JOIN m_sandi_kantor_cabang as b on a.id_cabang_pengirim = b.id                 
                LEFT JOIN m_sandi_kantor_cabang c on a.id_cabang_penerima = c.id                
                WHERE a.id_kelas = "'.Session::get('kelas').'"                
                and test_key_tgl = "'.date('Y-m-d', strtotime($date)).'"                                
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
