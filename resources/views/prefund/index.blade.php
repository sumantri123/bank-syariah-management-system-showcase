
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
                                <input type="hidden" id="idRekPin" name="idRekPin" class="{{$data['classFormControl']}}">
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
                                    <div class="row form_custom">
                                        <div class="col-md-3">
                                            <label style="color:blue; font-weight:bold" class="form-label">Kode Transaksi</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="no_bukti" value="" onkeydown="upperCaseF(this)" name="no_bukti">      
                                            <input type="hidden" class="{{$data['classFormControl']}}" id="id_jb" value="" name="id_jb">    
                                            <input type="hidden" class="{{$data['classFormControl']}}" id="id_rtgs" value="" name="id_rtgs">    
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
                                            <label style="color:blue; font-weight:bold" class="form-label">Payment Detail</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="payment_detail" value="" name="payment_detail">
                                            <label for="payment_detail" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>
                                        <div class="col-md-3">
                                            <label style="color:blue; font-weight:bold" class="form-label">From Member</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="from_member" value="" onkeydown="upperCaseF(this)" name="from_member">
                                            <label for="from_member" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>
                                        <div class="col-md-3">
                                            <label style="color:blue; font-weight:bold" class="form-label">To Member</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="to_member" value="" onkeydown="upperCaseF(this)" name="to_member">
                                            <label for="to_member" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>
                                        <div class="col-md-6">
                                            <label style="color:blue; font-weight:bold" class="form-label">Member to Member Information</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="member_information" value="" name="member_information" >
                                            <label for="member_information" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>									
                                        <div class="col-md-6">
                                            <label style="color:blue; font-weight:bold" class="form-label">Nominal</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="nominal" value="" onkeyup="formatRupiah(this)" name="nominal" >
                                            <label for="nominal" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>                                            
                                        <div class="col-md-3">
                                            <label style="color:blue; font-weight:bold" class="form-label">Sender's Ref</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="send_ref" value="" name="send_ref">
                                            <label for="send_ref" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>
                                        <div class="col-md-3">
                                            <label style="color:blue; font-weight:bold" class="form-label">Receiver's Ref</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="receiver_ref" value="" name="receiver_ref">
                                            <label for="receiver_ref" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>                                            
                                        <div class="col-md-12">
                                            <label style="color:blue; font-weight:bold" class="form-label">To Branch/Sub Branch</label>
                                            <hr>
                                        </div>                                            
                                        <div class="col-md-6">
                                            <label style="color:blue; font-weight:bold" class="form-label">By Order</label>
                                            @if (($LDaftarKliringBA)->isEmpty())
                                            <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                                <div class="text-white">Data Tidak Ada</div>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                            @else
                                            <select class="{{$data['classFormSelect2']}} clear" name="id_kliring" id="id_kliring" width="100%">
                                                <option value=""></option>
                                                @foreach($LDaftarKliringBA as $daftarKliringBA)
                                                <option value="{{ $daftarKliringBA->id }}" >{{ $daftarKliringBA->kode_kliring.' - '.ucfirst(trans($daftarKliringBA->nama_kliring)) }}</option>
                                                @endforeach                                                                                                    
                                            </select>  
                                            @endif                                              
                                            <label for="id_kliring" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>                                            
                                        <div class="col-md-6">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Kode Prefund</label>
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
                                        <!-- <div class="col-sm-12">
                                            <div class="form_custom1" id="show_table"></div>                            
                                        </div> -->
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

<script src="{{ asset('additional/js/prefund.js') }}"></script>
