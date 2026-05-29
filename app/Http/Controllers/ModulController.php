<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modul;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faq = Faq::get();
        return view('faq/index', compact('faq'));
    }

    public function getData()
    {
        // return 123;
        $faq = Faq::get();

        if($faq) {
            return response()->json([
                'status'=>'oke',
                'data' => $faq
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        //
    }

    private function validateRequest($request, $faq_id=0){
        $messages = [
            'required' => 'Kolom <b>:attribute</b> harus diisi.',
            'min' => 'Panjang minimal <b>:attribute</b> huruf.',
            'unique' => 'Data <b>:attribute</b> ":input" sudah ada, tidak boleh sama.',
        ];



        return Validator::make($request->all(), [
            "pertanyaan" => "required|min:5|unique:m_faq,pertanyaan".($faq_id ? ",".$faq_id.",faq_id" : "" ),
            "jawaban" => "required|min:5",
            "nomor_urut" => "required|numeric|unique:m_faq,nomor_urut".($faq_id ? ",".$faq_id.",faq_id" : "" )

        ], $messages);

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

        if($request->ajax()){

            if ($this->validateRequest($request)->fails()) {



                return response()->json([

                    'status'=>'insert_failed',

                    'error' => $this->validateRequest($request)->messages()

                    ]);

                // Session::flash('error', $validator->messages()->first());

                // return redirect()->back()->withInput();

            }



            $insertFaq =Faq::create([

                "pertanyaan"=> $request->pertanyaan,

                "jawaban"=> $request->jawaban,

                "nomor_urut"=> $request->nomor_urut,

                "user_record"=> 1,

                "dt_record"=> date("Y-m-d H:i:s")

            ]);

            // $embung = Embung::create($request->all());



            if($insertFaq) {

                return response()->json(['status'=>'insert_successful']);

            } else {

                return response()->json(['status'=>'insert_failed']);

            }

        } else {

            return redirect('/');

        }

        // return redirect()->route('kategori_paper.index')->with(['berhasil' => 'Data Kategori Paper Berhasil Ditambah']);



    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        //

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)

    {

        if($request->ajax()){

            if ($this->validateRequest($request, $id)->fails()) {



                return response()->json([

                    'status'=>'insert_failed',

                    'error' => $this->validateRequest($request, $id)->messages()

                    ]);

            }



            $uodateFaq = Faq::find($id)->update([

                "pertanyaan"=> $request->pertanyaan,

                "jawaban"=> $request->jawaban,

                "nomor_urut"=> $request->nomor_urut,

                "user_modified"=> 1,

                "dt_modified"=> date("Y-m-d H:i:s")

            ]);



            if($uodateFaq) {

                return response()->json(['status'=>'insert_successful']);

            } else {

                return response()->json(['status'=>'insert_failed']);

            }

        } else {

            return redirect('/');

        }



        // $this->validateRequest();

        // $insertKategori = KategoriPaper::find($id)->update([

        //     "kategori_paper_nama" => $request->kategori_paper,

        //     "user_modified"=> 1

        // ]);



        // return redirect()->route('kategori_paper.index')->with(['berhasil' => 'Data Kategori Paper Berhasil Dirubah']);

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        $query = faq::find($id)->delete();



        if($query) {

            return response()->json(['status'=>'delete_successful']);

        } else {

            return response()->json(['status'=>'delete_failed']);

        }

        // return redirect()->back()->with('berhasil', 'Data Kategori Paper Berhasil Dihapus');

    }



    public function deleteData(Request $request, $id)

    {



        if($request->ajax()){

            $query = Faq::find($id)->delete();



            if($query) {

                return response()->json(['status'=>'delete_successful']);

            } else {

                return response()->json(['status'=>'delete_failed']);

            }

        } else {

            return redirect('/');

        }

    }



}

