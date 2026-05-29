<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;

class InfoAngsuranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    public function index()
    {		        

        $data = array(
            'title' => 'Perkiraan Perhitungan Angsuran',            
            'subtitle' => Session::get('subtitle'),
            'btnAdd' => 'Tambah',
            'classFormControl' => 'form-control form-control-sm',
            'classFormSelect' => 'form-select form-select-sm',
            'classFormSelect2' => 'single-select',
            'classTable' => 'table table-sm table-bordered table-striped',            
        );         
                        
        //return view('informasi_angsuran/index', compact('data'));        
        $returnHTML = view('informasi_angsuran/index',compact('data'))->render();
        return response()->json( array('success' => true, 'html'=>$returnHTML) );        
    }    
    
    public function getData(Request $request)
    {           
      
        $jangkaWaktu = $request->jangkaWaktu;
        $jangkaWaktux = ($jangkaWaktu+1);
        $jenisPinjaman = $request->jenisAngsuran;       
        $nominal = str_replace(",","",$request->nominal);
		$nominalMarginMurabahah = (($request->jenisAngsuran) == 1) ? $request->bunga:0;        
        /* $sukuBunga = $request->bunga;
        $tagihanBunga = $nominal * $sukuBunga; */
        /* $irr = ($request->eir)/100;            
        $provisiPersen = ($request->provisi)/100;                     */
        $provisiNominal = $nominal; 
    
        for($a=0; $a<$jangkaWaktux; $a++){

            $date[$a] = ($a==0) ? date("Y-m-d") : $nextDate[$a-1];                        
            $estimasiAwal[$a] = 0 - ($nominal); 
            $saldoAkhirAwal[$a] = $nominal;           
            
            //generate tgl jatuh tempo                            
            $currentMonth[$a] = date("m",strtotime($date[$a]));
            $nextMonth[$a] = date("m",strtotime($date[$a]."+1 month"));
            
            if($currentMonth[$a]==($nextMonth[$a]-1) && (date("j",strtotime($date[$a])) != date("t",strtotime($date[$a])))){
                $nextDate[$a] = date('Y-m-d',strtotime($date[$a]." +1 month"));                                                
            }else{
                $nextDate[$a] = (date('d') > 28) ? date('Y-m-d', strtotime("last day of next month",strtotime($date[$a]))) : date('Y-m-d', strtotime("next month",strtotime($date[$a])));
            }
            
            if($a==0){
                $saldoAwal[$a] = 0;
				$totalTagihanBunga = 0;
				$totalAngsuranPokok = 0;
            } else if($a==1) {
                $saldoAwal[$a] = $saldoAkhirAwal[$a];
            } else {
                $saldoAwal[$a] = $saldoAkhir[$a-1];
            }
            
            /* $IRRNominal[$a] = $saldoAwal[$a];
            $amortisasi[$a] = $IRRNominal[$a] - $tagihanBunga; */			

            if(($jenisPinjaman)==1){
				if($a!=$jangkaWaktu){
					
					$angsuranPokok[$a] = ($a==0) ? 0: round(($nominal - $nominalMarginMurabahah)/$jangkaWaktu,0);
					$totalAngsuranPokok += $angsuranPokok[$a]; 
					$tagihanBunga[$a] = ($a==0) ? 0: round($nominalMarginMurabahah/$request->jangkaWaktu,0); //nominalMarginDitangguhkan                        					
					$totalTagihanBunga += $tagihanBunga[$a]; 
					
					//echo $a.')'.$angsuranPokok[$a].'-'.$totalAngsuranPokok.'<br>';
				} else {
					//echo $nominal.'-'.$totalAngsuranPokok;
					$angsuranPokok[$a] = ($a==0) ? 0: $nominal - $nominalMarginMurabahah - $totalAngsuranPokok;
					$tagihanBunga[$a] = ($a==0) ? 0: $nominalMarginMurabahah - $totalTagihanBunga;	
					
				}
            } else {
				
                $angsuranPokok[$a] = (($jangkaWaktu)==$a) ? $nominal:0;
            }    

			
            $estimasi[$a] = $angsuranPokok[$a] + $tagihanBunga[$a];
            $saldoAkhir[$a] = $saldoAwal[$a] - $angsuranPokok[$a] - $tagihanBunga[$a];

            $dataTable[] = [                            
                            "angsuran_ke"=> $a,
                            "tanggal_jth_tempo"=> ($a==0) ? $date[$a]:$nextDate[$a-1],
                            "estimasi"=> ($a==0) ? $estimasiAwal[$a]:$estimasi[$a],
                            "saldo_awal"=> $saldoAwal[$a],                            
                            "angsuran_pokok"=> ($a==0) ? 0:$angsuranPokok[$a],
                            "tagihan_bunga"=> ($a==0) ? 0:$tagihanBunga[$a],                            
                            "saldo_akhir"=> ($a==0) ? $saldoAkhirAwal[$a]:$saldoAkhir[$a],
                            "status"=> "n",
                            "tanggal_bayar"=> null,                            
                            "nomor_rekening"=> "106004.01051",
                            "jenis_pinjaman"=> "1",
                            "nominal_pokok"=> 50000000,
                            "jangka_waktu"=> 36,
                            "provisi_persen"=> 3,
                            "bunga_efektif_bulan"=> 2.358,
                            "bunga_efektif_anuitas"=> 15
                        ];
 
        }     

        if($dataTable) {           

            return response()->json([
                'status'=>'oke',
                'data' => $dataTable,
                //'noRek' => $dataTable[0]->nomor_rekening,
                'jenisPinjaman' => $jenisPinjaman,
                'nominalPinjaman' => $nominal,
                'jangkaWaktu' => $jangkaWaktu,                
                //'bungaNominal' => $nominal*($sukuBunga/100/12)*$jangkaWaktu,                
				'bungaNominal' => 0,                
                'provisiNominal' => $nominal,
                //'bungaPersen' => $sukuBunga,               
				'bungaPersen' => 0,               
                'total' => count($dataTable),                
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
