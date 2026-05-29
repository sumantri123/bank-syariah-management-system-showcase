<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\EditPerkiraan;
use Auth;
use Session;

class SaldoAwalController extends Controller
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
            'title' => 'Saldo Awal',
            'subtitle' => Session::get('subtitle'),
            'btnClass' => 'btn btn-primary btn-sm px-4',
            'btnAdd' => 'Tambah',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormControl' => 'form-control form-control-sm',
        );        
        //return view('saldo_awal/index', compact('data'));
        $returnHTML = view('saldo_awal/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );
    }       

    public function getData()
    {
        $EditPerkiraan = EditPerkiraan::where('id_lembaga','=',Session::get('idLembaga'))->get();

        if($EditPerkiraan) {
            return response()->json([
                'status'=>'oke',
                'data' => $EditPerkiraan
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
            "kode_perkiraan" => "required|unique:m_perkiraan,kode_perkiraan".($id ? ",".$id.",id" : "" ),
            "nama_perkiraan" => "required",			
        ], $messages);
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            // if ($this->validateRequest($request)->fails()) {
			// 	return response()->json([
            //         'status'=>'insert_failed',
            //         'error' => $this->validateRequest($request)->messages()
            //         ]);

            // }

            DB::beginTransaction();
            try {
                $insertPerkiraan = EditPerkiraan::create([
                    "kode_perkiraan"=> $request->kode_perkiraan,
                    "nama_perkiraan"=> $request->nama_perkiraan,								                
                    "id_jenis_transaksi"=> $request->keterangan,
					"id_lembaga"=> Session::get('idLembaga'),
                    "nominal_perkiraan"=> $request->nominal_perkiraan,
                ]);

                if($insertPerkiraan) {
                    DB::commit();
                    return response()->json(['status'=>'insert_successful']);
                } else {
                    return response()->json(['status'=>'insert_failed']);
                }
            } catch (\Throwable $e) {
                DB::rollback();            
                throw $e;            
                return response()->json(['status'=>'insert_failed']);
            }
        } else {
            return redirect('asset/');
        }

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

            $updateEditPerkiraan = EditPerkiraan::where('id', '=', $id)->update([
                "kode_perkiraan"=> $request->kode_perkiraan,
                "nama_perkiraan"=> $request->nama_perkiraan,								                
                "id_jenis_transaksi"=> $request->keterangan,
				"id_lembaga"=> Session::get('idLembaga'),
                "nominal_perkiraan"=> $request->nominal_perkiraan,
            ]);

            if($updateEditPerkiraan) {
                return response()->json(['status'=>'insert_successful']);
            } else {
                return response()->json(['status'=>'insert_failed']);
            }
        } else {
            return response()->json(['status'=>'proses_failed']);
        }

    }

}
