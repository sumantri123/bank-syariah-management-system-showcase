<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\NilaiTukar;
use App\Models\Kelas;
use Auth;
use Session;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {	
		
        $date = Carbon::now();	        
        $data = array(
            'now' => $date->format('d-m-Y'),            
        );               
        $LNilaiTukar = NilaiTukar::select('kurs_nama','kurs_beli','kurs_jual')->where('id_kelas','=',Session::get('kelas'))->orderby('kurs_nama','asc')->get();
        $LKelas = Kelas::select('name')->where('id','=',Session::get('kelas'))->get();

        return view('frontend/page/dashboard/index', compact('data','LNilaiTukar','LKelas'));        
        
    }                   
    
    public function kurs_footer(Request $request)
    {
        
        $LNilaiTukar = NilaiTukar::select('kurs_nama','kurs_beli','kurs_jual')->where('id_kelas','=',Session::get('kelas'))->get();
        $LKelas = Kelas::select('name')->where('id','=',Session::get('kelas'))->get();

        if($LNilaiTukar) {
            return response()->json(['status'=>'oke','dataKelas'=>$LKelas,'data'=>$LNilaiTukar]);
        } else {
            return response()->json(['status'=>'fail']);
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
