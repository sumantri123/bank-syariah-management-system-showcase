<h6 class="mb-0 text-uppercase">{{$data['title']}}</h6>
<hr/>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
            <input type="hidden" class="form-control" id="pass" value="{{$data['pass']}}" name="pass">
            <button id="tambah" data-bs-toggle="modal" data-bs-target="#exampleExtraLargeModal" class="{{$data['btnClass']}}">{{$data['btnAdd']}}</button><br><br>
            <table id="example2" class="table table-striped table-bordered" border="2">
                
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modal-form" id="exampleExtraLargeModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form class="form-horizontal form-label-left" id="form_nasabah" method="post">
                <div class="modal-body">                
                    @csrf
                    <input type="hidden" class="form-control" id="method_field" name="_method" value="POST" />
                    <input type="hidden" class="form-control" id="id" value="" name="id">
                    <div id="error-validation"></div>                                        
                        <div class="card">
                            <div class="card-body">
                            <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                                        </div>
                                        <h5 class="mb-0 text-primary">{{$data['title']}}</h5>                                        
                                    </div>
                                    <hr>	
                                    <div class="row">                                        
                                        <div class="col-4">
                                            <label for="inputCity" class="form-label">Jenis Deposito</label>
                                        </div>     
                                        <div class="col-8">
                                            <label for="inputCity" class="form-label">No Rekening</label>										
                                        </div>     
                                        <div class="col-md-4">                                        
                                            <select class="{{$data['classFormSelect']}} clearDisable" onchange="getval(this);" name="jenis_deposito" id="jenis_deposito">
                                                <option value="Rupiah">Rupiah</option>
                                               <!-- <option value="Valas">Valas</option>-->
                                            </select>
                                            <label for="jenis_deposito" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="{{$data['classFormControl']}}" id="no_rekening_1" value="" name="no_rekening_1"  readonly>
                                            <input type="hidden" class="{{$data['classFormControl']}}" id="id_perkiraan" value="" name="id_perkiraan"  readonly>
                                            <label for="no_rekening_1" generated="true" class="error"></label>
                                            <label id="validationError"></label>									
                                        </div>
                                        <div class="col-md-4">                                        
                                            <input type="text" class="{{$data['classFormControl']}}" id="no_rekening_2" value="" name="no_rekening_2" placeholder="Auto Generate" readonly>
                                            <label for="no_rekening_2" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>
                                        <div class="col-4">
                                            <label for="inputCity" class="form-label">Jangka Deposito</label>                                            
                                        </div>
                                        <div class="col-8">
                                            <label for="inputCity" class="form-label">Customer Number</label>
                                        </div>
                                        <div class="col-md-4">                                            
                                            <select class="{{$data['classFormSelect']}}" onchange="getvaljangka(this);" name="jangka_deposito" id="jangka_deposito">
                                                <option value=""></option>
                                                <option value="1">1 Bulan</option>
                                                <option value="3">3 Bulan</option>
                                                <option value="4">4 Bulan</option>
                                                <option value="12">12 Bulan</option>
                                            </select>                                           
                                            <label for="jangka_deposito" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>
                                        <div class="col-md-8">                                            
                                            @if (($LNasabahIndividu)->isEmpty())
                                            <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                                <div class="text-white">Silahkan Masukkan Data Customer Terlebih Dahulu</div>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                            @else
                                                <select class="{{$data['classFormSelect2']}} clearDisable" name="customer_number" id="customer_number">
                                                    <option value=""></option>
                                                    @foreach($LNasabahIndividu as $nasabahIndividu)
                                                    <option value="{{ $nasabahIndividu->id }}" >{{ $nasabahIndividu->cif.' - '.ucfirst(trans($nasabahIndividu->nama)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>
                                            @endif
                                            <label for="customer_number" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputCity" class="form-label">Tanggal Buka</label>
                                            <input type="text" class="{{$data['classFormControl']}} datepicker clearDisable" id="tgl" value="" name="tgl">
                                            <label for="tgl" generated="true" class="error"></label>
                                            <label id="validationError"></label>								
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputCity" class="form-label">Margin (% pa.)</label>
                                            <input type="text" class="{{$data['classFormControl']}}" maxlength="2" id="bunga" value="" name="bunga">    
                                            <label for="bunga" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div> 
                                        <div class="col-md-4">                                        
                                        <label for="inputCity" class="form-label">Sandi Pemilik</label>
                                            <select class="{{$data['classFormSelect']}} clearDisable" name="sandi_pemilik" id="sandi_pemilik">
                                                <option value=""></option>
                                                @foreach($LSandiPemilik as $sandiPemilik)
                                                <option value="{{ $sandiPemilik->id }}" >{{ ucfirst(trans($sandiPemilik->nama)) }}</option>
                                                @endforeach                                                                                                    
                                            </select>                                               
                                            <label for="sandi_pemilik" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>                                      
                                    </div>																
                            </div>						
                        </div>    
                    
                <div class="modal-footer">                    
                    <button type="button" id="btn_simpan" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('additional/js/nasabah_deposito.js?v=1.00') }}"></script>
