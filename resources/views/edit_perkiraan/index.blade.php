
<h6 class="mb-0 text-uppercase">{{$data['title']}}</h6>
<hr/>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
            <button id="tambah" class="{{$data['btnClass']}}">{{$data['btnAdd']}}</button><br><br>
            <table id="example2" class="table table-striped table-bordered" border="2">
                
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modal-form" tabindex="-1" id="exampleLargeModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_label">Form Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form-horizontal form-label-left" id="form_edit_perkiraan" method="post">
                <div class="modal-body">                
                    @csrf
                    <input type="hidden" class="form-control" id="method_field" name="_method" value="POST" />
                    <input type="hidden" class="form-control" id="id" value="" name="id">
                    <div id="error-validation"></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="inputPhoneNo" class="form-label"><b>No Perkiraan</b></label>
                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-plus-square' ></i></span>
                                <input type="number" class="form-control border-start-0" id="kode_perkiraan" name="kode_perkiraan" placeholder="No Perkiraan" />
                            </div>
                            <label for="kode_perkiraan" generated="true" class="error"></label>
                            <label id="validationError"></label>
                        </div>
                        <div class="col-12">
                            <label for="inputEmailAddress" class="form-label"><b>Nama Perkiraan</b></label>
                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
                                <input type="text" class="form-control border-start-0" id="nama_perkiraan" name="nama_perkiraan" placeholder="Nama Perkiraan" />
                            </div>
                            <label for="nama_perkiraan" generated="true" class="error"></label>
                            <label id="validationError"></label>
                        </div>
                        <div class="col-12">
                            <label for="inputEmailAddress" class="form-label"><b>Keterangan</b></label>
                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
                                <select class="{{$data['classFormSelect']}}" name="keterangan" id="keterangan">
                                    <option value=""></option>
                                    <option value="1">Debet</option>
                                    <option value="2">Kredit</option>                                                   
                                </select>
                                <label for="keterangan" generated="true" class="error"></label>
                                <label id="validationError"></label>
                            </div>
                            <label for="keterangan" generated="true" class="error"></label>
                            <label id="validationError"></label>
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

<script src="{{ asset('additional/js/edit_perkiraan.js?v=1.00') }}"></script>


