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
<div class="modal fade modal-form" tabindex="-1" id="exampleExtraLargeModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_label">Form Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            
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
                                        <div class="col-12">
                                            <label for="inputCity" class="form-label">Customer Number</label>
                                            @if (($LNasabahIndividu)->isEmpty())
                                            <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                                <div class="text-white">Rekening Giro Tidak Ada</div>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                            @else
                                                <select class="{{$data['classFormSelect2']}}" onchange="getval(this);" name="rekening_nasabah_id" id="rekening_nasabah_id">
                                                    <option value=""></option>
                                                    @foreach($LNasabahIndividu as $nasabahIndividu)
                                                    <option value="{{ $nasabahIndividu->tab_id }}" >{{ $nasabahIndividu->cif.' - '.$nasabahIndividu->nomor_rekening.' - '.ucfirst(trans($nasabahIndividu->nama)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>
                                            @endif
                                            <label for="rekening_nasabah_id" generated="true" class="error"></label>
                                            <label id="validationError"></label>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputCity" class="form-label">Nama Nasabah</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="nama" value="" name="nama" readonly>
                                            <input type="hidden" class="{{$data['classFormControl']}}" id="id_nasabah" value="" name="id_nasabah" readonly>
                                            <label for="nama" generated="true" class="error"></label>
                                            <label id="validationError"></label>								
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputCity" class="form-label">Alamat</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="alamat" value="" name="alamat" readonly>
                                            <label for="alamat" generated="true" class="error"></label>
                                            <label id="validationError"></label>								
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputCity" class="form-label">Kota</label>
                                            <input type="text" class="{{$data['classFormControl']}}" id="kota" value="" name="kota" readonly>    
                                            <label for="kota" generated="true" class="error"></label>
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

<script src="{{ asset('additional/js/blacklist_giro.js') }}"></script>
