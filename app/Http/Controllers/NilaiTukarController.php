<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\NilaiTukar;
use Auth;
use Session;

class NilaiTukarController extends Controller
{

    // use AuthenticatesUsers;
    protected $redirectTo = '/';

	public function __construct()
    {
        //$this->middleware('guest', ['except' => 'logout']);
    }

    public function index()
    {		
        $data = array(
            'title' => 'Input Kurs / Nilai Tukar Valas',
            'subtitle' => Session::get('subtitle'),
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
        );        
        //return view('nilai_tukar/index', compact('data'));
        $returnHTML = view('nilai_tukar/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
        $LNilaiTukar = NilaiTukar::where('id_kelas','=',Session::get('kelas'))->get();

        if($LNilaiTukar) {
            return response()->json([
                'status'=>'oke',
                'data' => $LNilaiTukar
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }
    
    private function validateRequest($request, $id=0){

        $messages = [
            'required' => 'Kolom <b>:attribute</b> harus diisi.',
            'min' => 'Panjang minimal <b>:attribute</b> huruf.',
            'unique' => 'Data <b>:attribute</b> ":input" sudah ada, tidak boleh sama.',
        ];

        return Validator::make($request->all(), [
            "kode" => "required|unique:m_daftar_kliring,kode_kliring".($id ? ",".$id.",id" : "" ),
            "nama_bank" => "required",			
        ], $messages);
    }    

    public function update(Request $request, $id)
    {
        if($request->ajax()){
            // if ($this->validateRequest($request, $id)->fails()) {

            //     return response()->json([
            //         'status'=>'insert_failed',
            //         'error' => $this->validateRequest($request, $id)->messages()
            //         ]);
            // }

            $update = NilaiTukar::where('id', '=', $id)->update([
                "kurs_nama"=> $request->kurs_nama,
                "kurs_beli"=> $request->kurs_beli,
                "kurs_jual"=> $request->kurs_jual,	                
                "updated_at"=> date("Y-m-d H:i:s")
            ]);

            if($update) {
                return response()->json(['status'=>'insert_successful']);
            } else {
                return response()->json(['status'=>'insert_failed']);
            }
        } else {
            return response()->json(['status'=>'proses_failed']);
        }

    }

    public function destroy(Request $request, $id)
    {
        if($request->ajax()){
            $query = DaftarKliring::find($id)->delete();
            if($query) {
                return response()->json(['status'=>'delete_successful']);
            } else {
                return response()->json(['status'=>'delete_failed']);
            }
        } else {
            return response()->json(['status'=>'delete_failed']);
        }
    }

}
