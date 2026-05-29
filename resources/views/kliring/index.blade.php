
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
                            <!-- <div class="border p-3 rounded"> -->
                            <form class="form-horizontal form-label-left" id="formEntry" method="post">    
                                @csrf                                                     
                                    <input type="hidden" class="form-control" id="kode" name="kode" value="{{$data['kode']}}" />
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
                                    
                                    <div class="border p-3 rounded">
                                        <center>                                    
                                            <div id='loading' style='display: none;'>                                        
                                                <button class="btn btn-warning" type="button" disabled> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                            </div>
                                        </center><br>
                                        <input type="hidden" class="{{$data['classFormControl']}}" id="id_perkiraan_raapkn" value="{{$data['idRaapkn']}}" name="id_perkiraan_raapkn">
                                        <input type="hidden" class="{{$data['classFormControl']}}" id="kode_perkiraan_raapkn" value="{{$data['kodeRaapkn']}}" name="kode_perkiraan_raapkn">
                                        <div class="row form_custom">
                                            <div class="col-md-3">
                                                <label style="color:blue; font-weight:bold" class="form-label">Kode Transaksi</label>
                                                <input type="text" class="{{$data['classFormControl']}}" id="no_bukti" value="" onkeydown="upperCaseF(this)" name="no_bukti">      
                                                <input type="hidden" class="{{$data['classFormControl']}}" id="id_jb" value="" name="id_jb">    
                                                <input type="hidden" class="{{$data['classFormControl']}}" id="id_kliring" value="" name="id_kliring">    
                                                <label for="no_bukti" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="color:blue; font-weight:bold" class="form-label">Tanggal</label>
                                                <input type="text" class="{{$data['classFormControl']}}" id="tgl" value="" name="tgl">
                                                <label for="tgl" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <label style="color:blue; font-weight:bold" class="form-label">Kode Bank Lain</label>
                                                @if (($LDaftarKliringBL)->isEmpty())
                                                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                                    <div class="text-white">Data Tidak Ada</div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                                @else
                                                <select class="{{$data['classFormSelect2']}} clear" name="kode_bank_lain" id="kode_bank_lain" width="100%">
                                                    <option value=""></option>
                                                    @foreach($LDaftarKliringBL as $daftarKliringBL)
                                                    <option value="{{ $daftarKliringBL->id }}" >{{ $daftarKliringBL->kode_kliring.' - '.ucfirst(trans($daftarKliringBL->nama_kliring)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>                                                
                                                @endif
                                                <label for="kode_bank_lain" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>                                            
                                            <div class="col-md-3">
                                                <label style="color:blue; font-weight:bold" class="form-label">No. Warkat</label>
                                                <input type="text" class="{{$data['classFormControl']}}" id="no_warkat" value="" onkeydown="upperCaseF(this)" name="no_warkat">
                                                <label for="no_warkat" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="color:blue; font-weight:bold" class="form-label">Rekening Bank Lain</label>
                                                <input type="text" class="{{$data['classFormControl']}}" id="rek_bank_lain" value="" onkeydown="upperCaseF(this)" name="rek_bank_lain">                                                
                                                <label for="rek_bank_lain" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <label style="color:blue; font-weight:bold" class="form-label">Kode Bank Asal</label>
                                                @if (($LDaftarKliringBA)->isEmpty())
                                                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                                    <div class="text-white">Data Tidak Ada</div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                                @else
                                                <select class="{{$data['classFormSelect2']}} clear" name="kode_bank_asal" id="kode_bank_asal" width="100%">
                                                    <option value=""></option>
                                                    @foreach($LDaftarKliringBA as $daftarKliringBA)
                                                    <option value="{{ $daftarKliringBA->id }}" >{{ $daftarKliringBA->kode_kliring.' - '.ucfirst(trans($daftarKliringBA->nama_kliring)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>  
                                                @endif                                              
                                                <label for="kode_bank_asal" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="color:blue; font-weight:bold" class="form-label">Sandi Transaksi</label>
                                                @if (($LSandiTransaksi)->isEmpty())
                                                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                                    <div class="text-white">Sandi Transaksi Tidak Ada</div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                                @else
                                                <select class="{{$data['classFormSelect2']}} clear" onchange="getval3(this);" name="sandi_transaksi" id="sandi_transaksi" width="100%">
                                                    <option value=""></option>
                                                    @foreach($LSandiTransaksi as $sandiTransaksi)
                                                    <option value="{{ $sandiTransaksi->id }}" >{{ $sandiTransaksi->kode_transaksi_kliring.' - '.ucfirst(trans($sandiTransaksi->nama_transaksi_kliring)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>                                                
                                                <input type="hidden" class="{{$data['classFormControl']}}" id="id_jenis_transaksi" value="" name="id_jenis_transaksi" >
                                                @endif
                                                <label for="sandi_transaksi" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>                                  
                                            <div class="col-md-3">
                                                <label style="color:blue; font-weight:bold" class="form-label">Nominal</label>
                                                <input type="text" class="{{$data['classFormControl']}}" id="nominal" value="" onkeyup="formatRupiah(this)" name="nominal">
                                                <label for="nominal" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>                                            
                                            <div class="col-md-6">
                                                <label style="color:blue; font-weight:bold" class="form-label">Jenis Warkat</label>
                                                <select class="{{$data['classFormSelect2']}} clear" name="jenis_warkat" id="jenis_warkat" >
                                                    <option value=""></option>                                                    
                                                    <option value="1" >01 - Cek</option>
                                                    <option value="2" >02 - Bilyet Giro</option>
                                                    <option value="3" >03 - Wesel Bank Untuk Transfer</option>
                                                    <option value="4" >04 - Srt Bukti Penerimaan Transfer</option>
                                                    <option value="5" >05 - Nota Debet</option>
                                                    <option value="6" >06 - Nota Kredit</option>
                                                </select>
                                                <label for="jenis_warkat" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="color:blue; font-weight:bold" class="form-label">Jenis Usaha</label>
                                                <input type="text" class="{{$data['classFormControl']}}" id="jenis_usaha" value="" onkeydown="upperCaseF(this)" name="jenis_usaha">
                                                <label for="jenis_usaha" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            <div class="col-md-3">
                                                <label style="color:blue; font-weight:bold" class="form-label">Penyelenggara Klirng</label>
                                                <input type="text" class="{{$data['classFormControl']}}" id="penyelenggara_kliring" value="" name="penyelenggara_kliring" >
                                                <label for="penyelenggara_kliring" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label style="color:blue; font-weight:bold" class="form-label">Transaksi</label>
                                                <input type="text" class="{{$data['classFormControl']}}" id="transaksi" value="" name="transaksi">
                                                <label for="transaksi" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>                                            
                                            <div class="col-md-12">
                                            <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Perkiraan Lawan</label>
                                                @if (($LEditPerkiraan)->isEmpty())
                                                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                                    <div class="text-white">Data Perkiraan Tidak Ada</div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                                @else
                                                <select class="{{$data['classFormSelect2']}} clear" onchange="getval2(this);" name="rek_lawan_perk" id="rek_lawan_perk" width="100%">
                                                    <option value=""></option>
                                                    @foreach($LEditPerkiraan as $editPerkiraan)
                                                    <option value="{{ $editPerkiraan->id }}" >{{ $editPerkiraan->kode_perkiraan.' - '.ucfirst(trans($editPerkiraan->nama_perkiraan)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>
                                                <input type="hidden" class="{{$data['classFormControl']}}" id="kode_perkiraan" value="" name="kode_perkiraan" >                                                
                                                @endif
                                                <label for="rek_lawan_perk" generated="true" class="error"></label>
                                                <label id="validationError"></label>    
                                            </div>                                                                                                                                                                                                                                                                     
                                        </div>
                                    </div>	                                    
								</form>
                            <!-- </div> -->
                        </main>                                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('additional/js/kliring.js') }}"></script>
