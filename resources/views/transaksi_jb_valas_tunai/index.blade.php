
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
								<input type="hidden" class="form-control" id="pendapatan_valas_id" name="pendapatan_valas_id" value="{{Session::get('4XPVALAS_ID')}}" />
                                <div class="row ">
                                    <nav class="navbar navbar-expand-sm navbar-dark bg-secondary rounded">
                                        <div class="container-fluid"> 
                                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent5" aria-controls="navbarSupportedContent5" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span></button>
                                            <div class="collapse navbar-collapse" id="navbarSupportedContent5">                                            
                                                <div class="col-md-6" align="center">
                                                    <div class="input-group" id="searchGrup"><span class="input-group-text bg-transparent"><i class="bx bx-search"></i></span>
                                                        <input type="text" id="search" name="search" class="{{$data['classFormControl']}}" onkeydown="upperCaseF(this)" placeholder="Cari Data dan Enter">
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
                                <div class="row form_custom">                                                                                                                                                                                                     
                                    <div class="col-md-2">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">No. Bukti</label>
                                        <input type="text" class="{{$data['classFormControl']}}" id="no_bukti" value="" onkeydown="upperCaseF(this)" name="no_bukti">    
                                        <input type="hidden" class="{{$data['classFormControl']}}" id="id_jb" value="" name="id_jb">    
                                        <label for="no_bukti" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Tanggal</label>
                                        <input type="text" class="{{$data['classFormControl']}}" id="tgl" value="" name="tgl">
                                        <label for="tgl" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Transaksi</label>
                                        <select class="{{$data['classFormSelect2']}} clear" onchange="getval(this);" name="transaksi" id="transaksi" >
                                            <option value=""></option>                                        
                                            <!--<option value="2.1" >BN01 - Penjualan Bank Notes</option>                                        
											<option value="2.2" >BN02 - Pembelian Bank Notes</option>                                        -->
											@foreach($LKursTukar as $NilaiTukar => $dataTukar)
												<?php
													$nama_tukar = explode(" ",$dataTukar->kurs_nama);
													$nama_alias = ($nama_tukar[1]=="BN")?"Bank Notes":"TC";
												?>
                                            <option value="{{$dataTukar->id}}.1" >{{$nama_tukar[1]}}01 - Penjualan {{$nama_alias}}</option>                                        
											<option value="{{$dataTukar->id}}.2" >{{$nama_tukar[1]}}02 - Pembelian {{$nama_alias}}</option>                                        
@endforeach
                                        </select>
                                        <label for="transaksi" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>   
                                    <div class="col-md-5">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Keterangan</label>
                                        <input type="text" class="{{$data['classFormControl']}}" id="keterangan" value="" name="keterangan">    
                                        <label for="keterangan" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>                                
                                </div>                                                        
                                                        
                                <hr>
                                <center>                                    
                                    <div id='loading' style='display: none;'>                                        
                                        <button class="btn btn-warning" type="button" disabled> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                </center><br>
                                <div class="form_custom1" id="show_table"></div>

                                <div class="alert alert-primary border-0 bg-primary alert-dismissible fade show py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-white"><i class='bx bx-message-square-add'></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-white">Note :</h6>
                                            <div class="text-white">Tekan F2 Untuk Menambah Data</div>
                                        </div>
                                    </div>                            
                                </div>
                            </form>
                        </main>                        
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('additional/js/transaksi_jb_valas_tunai.js?v=1.00') }}"></script>
