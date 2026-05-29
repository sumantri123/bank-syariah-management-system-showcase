
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="border p-3 rounded">
            <div id="invoice">            
                <div class="invoice overflow-auto">
                    <div id="transContent" style="min-width: 600px">
                        <header>
                            <div class="row">
                                <div class="d-flex align-items-center">  
									<div class="col-sm-1" align="center">                                    
										<img src="{{ URL::asset(session("logoHeaderTransaksi")) }}" alt="" />                
									</div>
									<div class="col-sm-11" style="margin-left:30px">
										<h4 class="name"><a href="javascript:;"><strong>{{$data['title']}}</strong></a></h4>
										<h6 class="name font-16"><a href="javascript:;">{{$data['subtitle']}}</a></h6>
									</div>                                                                           
								</div>
                            </div>
                        </header>
                        <main>
                            <form class="form-horizontal form-label-left" id="formEntry" method="post">    
                                @csrf                                                    
                                <input type="hidden" class="form-control" id="method_field" name="_method" value="POST" />                            
                                <input type="hidden" class="form-control" id="bagian" name="bagian" value="{{$data['kode']}}" />
                                <input type="hidden" class="{{$data['classFormControl']}}" id="idTr" value="" name="idTr">    
                                <div class="row ">
                                    <nav class="navbar navbar-expand-sm navbar-dark bg-secondary rounded">
                                        <div class="container-fluid"> 
                                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent5" aria-controls="navbarSupportedContent5" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span></button>
                                            <div class="collapse navbar-collapse" id="navbarSupportedContent5">                                            
                                                <div class="col-md-6" align="center">
                                                    <div class="input-group" id="searchGrup"><span class="input-group-text bg-transparent"><i class="bx bx-search"></i></span>
                                                        <input type="text" id="search" name="search" class="{{$data['classFormControl']}}" onkeydown="upperCaseF(this)" placeholder="Cari Data dan Enter">
                                                        <input type="hidden" class="form-control" id="pass" value="{{$data['pass']}}" name="pass">
                                                    </div>                                                    
                                                </div>
                                                <div class="col-md-6" align="center">
                                                    <button type="button" id="btn_search" class="btn btn-primary btn-sm btn-action"><i class='bx bx-search mr-1'></i>Cari</button>
                                                    <button type="button" id="btn_new" class="btn btn-primary btn-sm btn-action"><i class='bx bx-file mr-1'></i>Transaksi Baru</button>
                                                    <button type="button" id="btn_simpan" class="btn btn-success btn-sm btn-action"><i class='bx bx-save mr-1'></i>Simpan</button>
                                                    <button type="button" id="btn_delete" class="btn btn-danger btn-sm btn-action"><i class='bx bx-trash mr-1'></i>Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>   
                                </div><br>
                                <div class="border p-3 rounded">
                                    <div class="row form_custom"> 
                                        <center>                                    
                                            <div id='loading' style='display: none;'>                                        
                                                <button class="btn btn-warning" type="button" disabled> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                            </div>
                                        </center><br>
                                        <div class="col-sm-6">                                                                                                                            
                                            <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Bukti Slip</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="no_bukti" value="" name="no_bukti" onkeydown="upperCaseF(this)">
                                            <label for="no_bukti" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                            <br>

                                            <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">No. Rekening / No Perkiraan</label>
                                            @if (!$LNasabahIndividu)
                                            <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                                <div class="text-white">Data Rekening Tidak Ada</div>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                            @else
                                                <select class="{{$data['classFormSelect2']}} clearDisable" onchange="getval(this);" name="id_rek" id="id_rek" >
                                                    <option value=""></option>
                                                    @foreach($LNasabahIndividu as $nasabahIndividu)
                                                    <option value="{{ $nasabahIndividu->tab_id }}"  perkiraan="{{ $nasabahIndividu->id_perkiraan }}" norek="{{ $nasabahIndividu->tab_id }}" no_rekening="{{ $nasabahIndividu->nomor_rekening }}"  >{{ $nasabahIndividu->nomor_rekening.' - '.ucfirst(trans($nasabahIndividu->nama)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>
                                            <input type="hidden" class="{{$data['classFormControl']}}" id="id_perkiraan1" value="" name="id_perkiraan1" >
                                                <input type="hidden" class="{{$data['classFormControl']}}" id="id_tab" value="" name="id_tab" >
                                                <input type="hidden" class="{{$data['classFormControl']}}" id="no_rekening" value="" name="no_rekening" >
                                            @endif
                                            <label for="id_rek" generated="true" class="error"></label>
                                            <label id="validationError"></label>

                                            <div class="row"> 
                                                <div class="col-md-4">
                                                    <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Tanggal</label>
                                                    <input type="text" class="{{$data['classFormControl']}}" id="tgl" value="" name="tgl" >
                                                    <label for="tgl" generated="true" class="error"></label>
                                                    <label id="validationError"></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Kode Transaksi</label>
                                                    <select class="{{$data['classFormSelect3']}} clearDisable" onchange="getval2(this);" name="id_kode_tran" id="id_kode_tran" width="100%">
                                                        <option value=""></option>
                                                        @foreach($LKodeTransaksi2 as $kodeTransaksi2)
                                                        <option value="{{ $kodeTransaksi2->id }}" >{{ $kodeTransaksi2->kode_transaksi.' - '.ucfirst(trans($kodeTransaksi2->nama_transaksi)) }}</option>
                                                        @endforeach                                                                                                    
                                                    </select>
                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="kode_transaksi" value="" name="kode_transaksi" >
                                                    <label for="id_kode_tran" generated="true" class="error"></label>
                                                    <label id="validationError"></label>
                                                </div>
                                            </div>
                                            <div class="row">                                                                                         
                                                <div class="col-md-4">
                                                    <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Nominal</label>
                                                    <input type="text" class="{{$data['classFormControl']}}" id="nominal" value="" onkeyup="formatRupiah(this)" name="nominal">
                                                    <label for="nominal" generated="true" class="error"></label>
                                                    <label id="validationError"></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Keterangan</label>
                                                    <input type="text" class="{{$data['classFormControl']}}" id="keterangan" value="" onkeydown="upperCaseF(this)" name="keterangan">
                                                </div>
                                            </div>                                                                                                                                                                    
                                        </div>        
                                        <div class="col-sm-6">
                                            <div class="form_custom1" id="show_table"></div>                            
                                        </div>        
                                    </div>
                                </form>
                            </div>
                        </main>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('additional/js/transaksi_penerimaan_tunai.js') }}"></script>
