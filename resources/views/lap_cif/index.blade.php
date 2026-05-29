<h6 class="mb-0 text-uppercase">{{$data['title']}}</h6>
<hr/>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered" border="2">
                
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modal-form" id="exampleExtraLargeModal" role="dialog" aria-hidden="true">
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
                    <div class="row g-3">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i></div>                                            <div class="tab-title">Data 1</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i></div>
                                            <div class="tab-title">Data 2</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#primarycontact" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i></div>
                                            <div class="tab-title">Data 3</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#primarycontact2" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i></div>
                                            <div class="tab-title">Data 4</div>                                        
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#primarycontact3" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i></div>
                                            <div class="tab-title">Data 5</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                                    <table class="{{$data['classTable']}}">
                                        <tr>
                                            <td width="15%">CIF Number</td>
                                            <td width="2%">:</td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" id="cif_1" value="" placeholder="{{$data['cif_1']}}" name="cif_1" readonly>
                                                <label for="cif_1" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td colspan=4>
                                                <input type="text" class="form-control form-control-sm" id="cif_2" value="" name="cif_2" placeholder="Auto Generate" maxlength="5" readonly>                                                
                                            </td>
                                            <td colspan=4>
                                                <input type="text" class="form-control form-control-sm" id="cif_3" value="" name="cif_3" placeholder="{{$data['cif_3']}}" readonly>
                                                <label for="cif_3" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Identitas</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="id_identitas" id="id_identitas">
                                                    <option value=""></option>
                                                    @foreach($LJenisIdentitas as $identitas)
                                                    <option value="{{ $identitas->id }}" >{{ ucfirst(trans($identitas->name)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>
                                                <label for="id_identitas" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">No. Identitas</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="no_identitas" value="" name="no_identitas">
                                                <label for="no_identitas" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Masa Berlaku s/d</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}} datepicker" id="msb_identitas" value="" name="msb_identitas">
                                                <label for="msb_identitas" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Nama Lengkap</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="nama_nasabah" value="" name="nama_nasabah">
                                                <label for="nama_nasabah" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Jenis Kelamin</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="id_jenis_kelamin" id="id_jenis_kelamin">
                                                    <option value=""></option>
                                                    <option value="L">Laki-Laki</option>
                                                    <option value="P">Perempuan</option>                                                   
                                                </select>
                                                <label for="id_jenis_kelamin" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Agama</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="id_agama" id="id_agama" >
                                                    <option value=""></option>
                                                    @foreach($LAgama as $agama)
                                                    <option value="{{ $agama->id }}" >{{ ucfirst(trans($agama->name)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>
                                                <label for="id_agama" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Tempat Lahir</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="tempat_lahir" value="" name="tempat_lahir">
                                                <label for="tempat_lahir" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Tanggal Lahir</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}} datepicker" id="tgl_lahir" value="" name="tgl_lahir"/>
                                                <label for="tgl_lahir" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Status</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="id_status" id="id_status" >
                                                    <option value=""></option>
                                                    @foreach($LStatus as $status)
                                                    <option value="{{ $status->id }}" >{{ ucfirst(trans($status->name)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>
                                                <label for="id_status" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Kewarganegaraan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kewarganegaraan" value="" name="kewarganegaraan">
                                                <label for="kewarganegaraan" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Pedidikan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="id_pendidikan" id="id_pendidikan" >
                                                    <option value=""></option>
                                                    @foreach($LPendidikan as $pendidikan)
                                                    <option value="{{ $pendidikan->id }}" >{{ ucfirst(trans($pendidikan->name)) }}</option>
                                                    @endforeach                                                                                                    
                                                </select>
                                                <label for="id_pendidikan" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">NPWP</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="npwp" value="" name="npwp">
                                                <label for="npwp" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Alamat Sesuai ID</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="alamat" value="" name="alamat">
                                                <label for="alamat" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">RT</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="rt" value="" name="rt">
                                                <label for="rt" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">RW</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="rw" value="" name="rw">
                                                <label for="rw" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Kelurahan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kelurahan" value="" name="kelurahan">
                                                <label for="kelurahan" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Kecamatan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kecamatan" value="" name="kecamatan">
                                                <label for="kecamatan" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Kota</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kota" value="" name="kota">
                                                <label for="kota" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Kode Pos</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kode_pos" value="" name="kode_pos">
                                                <label for="kode_pos" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Telp</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="telp" value="" name="telp">
                                                <label for="telp" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>                                
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                                    <table cellpadding="2" class="{{$data['classTable']}}">                           
                                        <tr>
                                            <td width="10%">Alamat Domisili</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="alamat_domisili" value="" name="alamat_domisili">
                                                <label for="alamat_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">RT</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="rt_domisili" value="" name="rt_domisili">
                                                <label for="rt_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">RW</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="rw_domisili" value="" name="rw_domisili">
                                                <label for="rw_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Kelurahan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kelurahan_domisili" value="" name="kelurahan_domisili">
                                                <label for="kelurahan_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Kecamatan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kecamatan_domisili" value="" name="kecamatan_domisili">
                                                <label for="kecamatan_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Kota</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kota_domisili" value="" name="kota_domisili">
                                                <label for="kota_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Kode Pos</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kodepos_domisili" value="" name="kodepos_domisili">
                                                <label for="kodepos_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Telp</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="telp_domisili" value="" name="telp_domisili">
                                                <label for="telp_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Hp</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="hp_domisili" value="" name="hp_domisili">
                                                <label for="hp_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Fax</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="fax_domisili" value="" name="fax_domisili">
                                                <label for="fax_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Email</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="email" class="{{$data['classFormControl']}}" id="email_domisili" value="" name="email_domisili">
                                                <label for="email_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Status Rumah</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="status_rumah_domisili" id="status_rumah_domisili">
                                                    <option value=""></option>
                                                    <option value="Milik Sendiri">Milik Sendiri</option>
                                                    <option value="Sewa">Sewa</option>
                                                    <option value="Menumpang">Menumpang</option>
                                                </select>
                                                <label for="status_rumah_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>                                                        
                                        <tr>
                                            <td width="10%">Alamat Korespondensi</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="alamat_korespondensi" id="alamat_korespondensi">
                                                    <option value=""></option>
                                                    <option value="1">Alamat Dasar</option>
                                                    <option value="2">Sesuai Kartu ID</option>
                                                    <option value="3">Sesuai R/K & Passbook</option>
                                                    <option value="4">Alamat Domisili</option>
                                                    <option value="5">Alamat Korespondensi 2</option>
                                                    <option value="6">Alamat Korespondensi 3</option>
                                                    <option value="7">Alamat Korespondensi 4</option>
                                                </select>
                                                <label for="alamat_korespondensi" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Nama Ibu Kandung</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="nm_ibu_kandung" value="" name="nm_ibu_kandung">
                                                <label for="nm_ibu_kandung" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>                                
                                        </tr>
                                    </table>    
                                </div>
                                <div class="tab-pane fade" id="primarycontact" role="tabpanel">
                                    <table cellpadding="2" class="{{$data['classTable']}}">
                                        <tr><td colspan=11><b>Pihak Yang Dapat Dihubungi dalam Keadaan Darurat</b></td></tr>
                                        <tr>
                                            <td width="10%">Nama Lengkap</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="nama_saudara" value="" name="nama_saudara">
                                                <label for="nama_saudara" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Hubungan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="hubungan_saudara" value="" name="hubungan_saudara">
                                                <label for="hubungan_saudara" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>                                
                                        </tr>
                                        <tr>
                                            <td width="10%">Alamat</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="alamat_saudara" value="" name="alamat_saudara">
                                                <label for="alamat_saudara" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Kota</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kota_saudara" value="" name="kota_saudara">
                                                <label for="kota_saudara" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Kode Pos</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kodepos_saudara" value="" name="kodepos_saudara">
                                                <label for="kodepos_saudara" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Hp</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="hp_saudara" value="" name="hp_saudara">
                                                <label for="hp_saudara" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Telepon</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="telp_saudara" value="" name="telp_saudara">
                                                <label for="telp_saudara" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Fax</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="fax_saudara" value="" name="fax_saudara">
                                                <label for="fax_saudara" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>                            
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="primarycontact2" role="tabpanel">
                                    <table cellpadding="2" class="{{$data['classTable']}}">
                                        <tr>
                                            <td width="10%">Pekerjaan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="pekerjaan" id="pekerjaan">
                                                    <option value=""></option>
                                                    @foreach($LPekerjaan as $pekerjaan)
                                                    <option value="{{ $pekerjaan->id }}" >{{ ucfirst(trans($pekerjaan->nama)) }}</option>
                                                    @endforeach   
                                                </select>
                                                <label for="pekerjaan" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Penghasilan/Bulan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="penghasilan_domisili" id="penghasilan_domisili">
                                                    <option value=""></option>
                                                    @foreach($LPenghasilan as $penghasilan)
                                                    <option value="{{ $penghasilan->id }}" >{{ ucfirst(trans($penghasilan->nama)) }}</option>
                                                    @endforeach   
                                                </select>
                                                <label for="penghasilan_domisili" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Nama Kantor</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="nama_kantor" value="" name="nama_kantor">
                                                <label for="nama_kantor" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>                                
                                        </tr>
                                        <tr>
                                            <td width="10%">Kegiatan Usaha</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kegiatan_usaha" value="" name="kegiatan_usaha">
                                                <label for="kegiatan_usaha" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Alamat Kantor</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="alamat_kantor" value="" name="alamat_kantor">
                                                <label for="alamat_kantor" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Kota</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kota_kantor" value="" name="kota_kantor">
                                                <label for="kota_kantor" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Kode Pos</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kodepos_kantor" value="" name="kodepos_kantor">
                                                <label for="kodepos_kantor" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Telepon Kantor</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="telp_kantor" value="" name="telp_kantor">
                                                <label for="telp_kantor" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Ext</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="ext_kantor" value="" name="ext_kantor">
                                                <label for="ext_kantor" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Fax</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="fax_kantor" value="" name="fax_kantor">
                                                <label for="fax_kantor" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Jabatan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="jabatan_kantor" value="" name="jabatan_kantor">
                                                <label for="jabatan_kantor" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Unit Kerja/Bagian</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="unit_kantor" value="" name="unit_kantor">
                                                <label for="unit_kantor" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>                            
                                    </table>                                
                                </div>
                                <div class="tab-pane fade" id="primarycontact3" role="tabpanel">
                                    <table cellpadding="2" class="{{$data['classTable']}}">
                                        <tr>
                                            <td width="10%">Sumber Dana</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="sumber_dana" id="sumber_dana">
                                                    <option value=""></option>
                                                    @foreach($LSumberDana as $sumber_dana)
                                                    <option value="{{ $sumber_dana->id }}" >{{ ucfirst(trans($sumber_dana->nama)) }}</option>
                                                    @endforeach   
                                                </select>
                                                <label for="sumber_dana" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Transaksi Tertinggi per Bulan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="traksaksi_perbulan" value="" name="traksaksi_perbulan" maxlength="3">
                                                <label for="traksaksi_perbulan" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Pernah Masuk DHBI</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="masuk_dhbi" id="masuk_dhbi">
                                                    <option value=""></option>
                                                    <option value="y">Ya</option>
                                                    <option value="n">Tidak</option>                                                    
                                                </select>
                                                <label for="masuk_dhbi" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>                                
                                        </tr>
                                        <tr>
                                            <td width="10%">Nama Ahli Waris</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="ahli_waris" value="" name="ahli_waris">
                                                <label for="ahli_waris" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Hubungan</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="hubungan_ahli_waris" value="" name="hubungan_ahli_waris">
                                                <label for="hubungan_ahli_waris" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Tujuan Buka Rekening</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="tuj_buka_rekening" id="tuj_buka_rekening">
                                                    <option value=""></option>
                                                    <option value="1">Usaha</option>
                                                    <option value="2">Menabung</option>
                                                    <option value="3">Lainnya</option>                                                    
                                                </select>
                                                <label for="tuj_buka_rekening" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%">Alamat</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="alamat_ahli_waris" value="" name="alamat_ahli_waris">
                                                <label for="alamat_ahli_waris" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Kota</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <input type="text" class="{{$data['classFormControl']}}" id="kota_ahli_waris" value="" name="kota_ahli_waris">
                                                <label for="kota_ahli_waris" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                            <td width="2%"></td>
                                            <td width="10%">Penggunaan Dana</td>
                                            <td width="2%">:</td>
                                            <td width="15%">
                                                <select class="{{$data['classFormSelect']}}" name="penggunaan_dana" id="penggunaan_dana">
                                                    <option value=""></option>
                                                    <option value="1">Usaha/Wiraswasta</option>
                                                    <option value="2">Biaya Sekolah/Kuliah</option>
                                                    <option value="3">Kebutuhan Rumah Tangga</option>
                                                    <option value="4">Lainnya</option>
                                                </select>
                                                <label for="penggunaan_dana" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </td>
                                        </tr>                            
                                    </table>                                  
                                </div>
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
<script src="{{ asset('additional/js/lap_cif.js') }}"></script>




