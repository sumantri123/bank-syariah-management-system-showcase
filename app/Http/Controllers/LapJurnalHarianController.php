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

class LapJurnalHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index($kode)
    {		    
        $kodeBagian = base64_decode($kode);
        switch ($kodeBagian)
        {
            case ("CS") : $namaBagian = 'Customer Service'; break;
            case ("JM") : $namaBagian = 'Import'; break;
            case ("JX") : $namaBagian = 'Export'; break;
            case ("AK") : $namaBagian = 'Akuntansi'; break;
            case ("PB") : $namaBagian = 'Kliring'; break;
            case ("JD") : $namaBagian = 'Deposito'; break;
            case ("TF") : $namaBagian = 'Transfer'; break;
            case ("LA") : $namaBagian = 'Admin Pembiayaan'; break; 
            case ("AT") : $namaBagian = 'Teller / Tabungan'; break;                                 
            case ("JG") : $namaBagian = 'Giro';
            default : 
                $namaBagian = '';
        }
        
        $data = array(
            'title' => 'Jurnal Harian '.$namaBagian,
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
            'kode' => $kode
        );         
        
        //return view('lap_jurnal_harian/index', compact('data'));        
        $returnHTML = view('lap_jurnal_harian/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
        
    }            
   

    public function getData(Request $request)
    {
        $date = $request->date;
        $kodeBagian = base64_decode($request->kode);

        $dataTable = DB::table('t_jurnal_bagian as a')
            ->leftJoin('t_jurnal_bagian_detail as b', 'a.jurnal_bagian_id', '=', 'b.id_jurnal_bagian')
            ->leftJoin('m_perkiraan as c', 'b.id_perkiraan', '=', 'c.id')
            ->select('a.*', 'b.*', 'c.id', 'c.kode_perkiraan', 'c.nama_perkiraan', 'c.df_trans_perkiraan', 'c.nominal_perkiraan') 
            ->where('jurnal_tanggal','=',date('Y-m-d', strtotime($date))) 
			->where('a.id_kelas', '=', Session::get('kelas'))  
			->where('c.id_lembaga', '=', Session::get('idLembaga'))				
			->where('jurnal_keterangan', '!=', 'Data Saldo Awal')
            ->orderBy('jurnal_no', 'asc')
            ->get();  

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
