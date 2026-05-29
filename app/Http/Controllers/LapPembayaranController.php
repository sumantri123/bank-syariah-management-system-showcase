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

class LapPembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		                    
        
        $data = array(
            'title' => 'DAFTAR PEMBAYARAN',
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',
            
        );         
        
        //return view('lap_pembayaran/index', compact('data'));        
        $returnHTML = view('lap_pembayaran/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }            
   

    public function getData(Request $request)
    {
        $date = $request->date;        

        $dataTable = DB::table('t_jurnal_bagian_detail as a')
            ->leftJoin('t_jurnal_bagian as b', 'a.id_jurnal_bagian', '=', 'b.jurnal_bagian_id')            
            ->leftJoin('m_kode_transaksi as c', 'a.id_kode_transaksi', '=', 'c.id')            
            ->select('a.*', 'b.*') 
            ->where('jurnal_tanggal','=',date('Y-m-d', strtotime($date))) 
            ->where('b.id_kelas', '=', Session::get('kelas'))
            ->where('a.id_perkiraan', '=', Session::get('1XXKATEL_ID'))
            ->where('c.jenis_transaksi', '=', 1)
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
