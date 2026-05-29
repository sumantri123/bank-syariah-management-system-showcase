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
                        <form class="form-horizontal form-label-left" id="formEntry" method="post">    
                            @csrf                                                    
                            <input type="hidden" class="form-control" id="method_field" name="_method" value="POST" />
                            <input type="hidden" class="form-control" id="bagian" name="bagian" value="{{$data['kode']}}" />                               
                            <main>
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
                                    <div class="border p-3 rounded">                              
                                        <center>                                    
                                            <div id='loading' style='display: none;'>                                        
                                                <button class="btn btn-warning" type="button" disabled> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                            </div>
                                        </center><br>
                                        <ul class="nav nav-tabs nav-primary" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                                        </div>
                                                        <div class="tab-title">Data I</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab" aria-selected="false">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                                        </div>
                                                        <div class="tab-title">Data II</div>
                                                    </div>
                                                </a>
                                            </li>                                    
                                        </ul>
                                        <div class="tab-content py-3">
                                            <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                                                <div class="row form_custom"> 
                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="idTr" value="" name="idTr">    
                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="idRek" value="" name="idRek">    
                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="idJb0" value="" name="idJb0">                                                        

                                                    <div class="col-sm-12">                                                                                                                        
                                                        <div class="row">                                             
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">No. Rekening</label>
                                                                @if (($LNasabahIndividu)->isEmpty())
                                                                <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                                                    <div class="text-white">Data Rekening Tidak Ada</div>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                                </div>
                                                                @else
                                                                    <select class="{{$data['classFormSelect2']}} clear" onchange="getval(this);" name="id_rekening2" id="id_rekening2" >
                                                                        <option value=""></option>
                                                                        @foreach($LNasabahIndividu as $nasabahIndividu)
                                                                        <option value="{{ $nasabahIndividu->tab_id }}" >{{ $nasabahIndividu->nomor_rekening.' - '.ucfirst(trans($nasabahIndividu->nama)) }}</option>
                                                                        @endforeach                                                                                                    
                                                                    </select>
                                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="id_perkiraan1" value="" name="id_perkiraan1" >
                                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="id_pinjaman" value="" name="id_pinjaman" >
                                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="jenis_pinjaman" value="" name="jenis_pinjaman" >
                                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="idNasabah" value="" name="idNasabah" >
                                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="no_rekening" value="" name="no_rekening" >
                                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="id_perkiraan_provisi" value="" name="id_perkiraan_provisi" >
                                                                    <input type="hidden" class="{{$data['classFormControl']}}" id="kode_perkiraan_provisi" value="" name="kode_perkiraan_provisi" >
																	<input type="hidden" class="{{$data['classFormControl']}}" id="jenis_rekening" value="" name="jenis_rekening" >
                                                                @endif
                                                                <label for="id_rekening2" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-1">
                                                            <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Ke</label>                      
                                                                <input type="text" class="{{$data['classFormControl']}}" id="ke" value="" name="ke" >
                                                                <label for="ke" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">No. Nasabah</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="cif" value="" name="cif" >
                                                                <label for="cif" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Nama</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="nama" value="" name="nama" >
                                                                <label for="nama" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Alamat</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="alamat" value="" name="alamat" >
                                                                <label for="alamat" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                        </div>
                                                        <div class="row">                                             
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Golongan Debitur</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_golongan_debitur" id="id_golongan_debitur" >
                                                                    <option value=""></option>
                                                                    @foreach($LGolonganDebitur as $GolonganDebitur)
                                                                    <option value="{{ $GolonganDebitur->id }}" >{{ $GolonganDebitur->kode.'-'.ucfirst(trans($GolonganDebitur->nama)) }}</option>
                                                                    @endforeach                                                                                                    
                                                                </select>
                                                                <label for="id_golongan_debitur" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Sifat Kredit</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_sifat_kredit" id="id_sifat_kredit" >
                                                                    <option value=""></option>
                                                                    @foreach($LSifatKredit as $SifatKredit)
                                                                    <option value="{{ $SifatKredit->id }}" >{{ $SifatKredit->kode.'-'.ucfirst(trans($SifatKredit->nama)) }}</option>
                                                                    @endforeach                                                                                                    
                                                                </select>
                                                                <label for="id_sifat_kredit" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Jenis Penggunaan</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_jenis_penggunaan" id="id_jenis_penggunaan" >
                                                                    <option value=""></option>
                                                                    @foreach($LJenisPenggunaan as $JenisPenggunaan)
                                                                    <option value="{{ $JenisPenggunaan->id }}" >{{ $JenisPenggunaan->kode.'-'.ucfirst(trans($JenisPenggunaan->nama)) }}</option>
                                                                    @endforeach                                                                                                    
                                                                </select>
                                                                <label for="id_jenis_penggunaan" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Sektor Ekonomi</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_sektor_ekonomi" id="id_sektor_ekonomi" >
                                                                    <option value=""></option>
                                                                    @foreach($LSektorEkonomi as $SektorEkonomi)
                                                                    <option value="{{ $SektorEkonomi->id }}" >{{ $SektorEkonomi->kode.'-'.ucfirst(trans($SektorEkonomi->nama)) }}</option>
                                                                    @endforeach                                                                                                    
                                                                </select>
                                                                <label for="id_sektor_ekonomi" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                        </div>
                                                        <div class="row">                                             
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Keterkaitan</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_keterkaitan" id="id_keterkaitan" >
                                                                    <option value=""></option>                                                    
                                                                    <option value="1" >Terkait</option>
                                                                    <option value="2" >Tidak Terkait</option>
                                                                </select>
                                                                <label for="id_keterkaitan" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Sumber Dana</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_sumber_dana" id="id_sumber_dana" >
                                                                    <option value=""></option>
                                                                    @foreach($LSumberDana as $SumberDana)
                                                                    <option value="{{ $SumberDana->id }}" >{{ $SumberDana->kode.'-'.ucfirst(trans($SumberDana->nama)) }}</option>
                                                                    @endforeach                                                                                                    
                                                                </select>
                                                                <label for="id_sumber_dana" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Periode Bayar</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_periode_bayar" id="id_periode_bayar" >
                                                                    <option value=""></option>
                                                                    @foreach($LPeriodeBayar as $PeriodeBayar)
                                                                    <option value="{{ $PeriodeBayar->id }}" >{{ $PeriodeBayar->kode.'-'.ucfirst(trans($PeriodeBayar->nama)) }}</option>
                                                                    @endforeach                                                                                                    
                                                                </select>
                                                                <label for="id_periode_bayar" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Lokasi Debitur</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_lokasi_debitur" id="id_lokasi_debitur" >
                                                                    <option value=""></option>
                                                                    @foreach($LLokasiDebitur as $LokasiDebitur)
                                                                    <option value="{{ $LokasiDebitur->id }}" >{{ $LokasiDebitur->kode.'-'.ucfirst(trans($LokasiDebitur->nama)) }}</option>
                                                                    @endforeach                                                                                                    
                                                                </select>
                                                                <label for="id_lokasi_debitur" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>                                            
                                                        </div>
                                                        <div class="row">                                             
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Penjamin</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_penjamin" id="id_penjamin" >
                                                                    <option value=""></option>
                                                                    @foreach($LPenjamin as $Penjamin)
                                                                    <option value="{{ $Penjamin->id }}" >{{ $Penjamin->kode.'-'.ucfirst(trans($Penjamin->nama)) }}</option>
                                                                    @endforeach                                                                                                    
                                                                </select>
                                                                <label for="id_penjamin" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Jenis Usaha</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_jenis_usaha" id="id_jenis_usaha" >
                                                                    <option value=""></option>
                                                                    <option value="1" >Mikro</option>
                                                                    <option value="2" >Kecil</option>
                                                                    <option value="3" >Menengah</option>
                                                                    <option value="4" >Selain UMKM</option>
                                                                </select>
                                                                <label for="id_jenis_usaha" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Jenis Angunan</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_jenis_angunan" id="id_jenis_angunan" >
                                                                    <option value=""></option>
                                                                    @foreach($LJenisAngunan as $JenisAngunan)
                                                                    <option value="{{ $JenisAngunan->id }}" >{{ $JenisAngunan->kode.'-'.ucfirst(trans($JenisAngunan->nama)) }}</option>
                                                                    @endforeach                                                                                                    
                                                                </select>
                                                                <label for="id_jenis_angunan" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Ikatan</label>
                                                                <select class="{{$data['classFormSelect2']}} clear" name="id_ikatan" id="id_ikatan" onchange="getval1(this);">
                                                                    <option value=""></option>
                                                                    @foreach($LIkatan as $Ikatan)
                                                                    <option value="{{ $Ikatan->id }}" >{{ $Ikatan->kode.'-'.ucfirst(trans($Ikatan->nama)) }}</option>
                                                                    @endforeach                                                                                                    
                                                                </select>
                                                                <label for="id_ikatan" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-2">            
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">%</label>                                    
                                                                <input type="text" class="{{$data['classFormControl']}}" id="persentase_ikatan" value="" name="persentase_ikatan" >
                                                                <label for="persentase_ikatan" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                        </div>

                                                        <div class="row">                                             
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Bagian Dijamin</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="bagian_dijamin" value="" name="bagian_dijamin" >
                                                                <label for="bagian_dijamin" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Nilai Agunan</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="nilai_angunan" value="" onkeyup="formatRupiah2(this)" name="nilai_angunan" >
                                                                <label for="nilai_angunan" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Taksasi Agunan</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="taksasi_agunan" value="" onkeyup="formatRupiah2(this)" name="taksasi_agunan" >
                                                                <label for="taksasi_agunan" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div> 
                                                            <div class="col-md-3">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Kode AO</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="kode_ao" value="" name="kode_ao" >
                                                                <label for="kode_ao" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>                                                                                                  
                                                        </div>   

                                                        <div class="row">         
                                                            <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Jaminan</label>                                    
                                                            <div class="col-md-3">                                                            
                                                                <input type="text" class="{{$data['classFormControl']}}" id="jaminan_1" value="" name="jaminan[]" >
                                                                <label for="jaminan_1" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="{{$data['classFormControl']}}" id="jaminan_2" value="" name="jaminan[]" >
                                                                <label for="jaminan_2" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="{{$data['classFormControl']}}" id="jaminan_3" value="" name="jaminan[]" >
                                                                <label for="jaminan_3" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="{{$data['classFormControl']}}" id="jaminan_4" value="" name="jaminan[]" >
                                                                <label for="jaminan_4" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>                                            
                                                        </div>                                                    
                                                    </div>                                            
                                                </div>                                               
                                            </div>
                                            <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                                                <div class="row form_custom"> 
                                                    <div class="col-sm-12"> 
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Tanggal Realisasi</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="tgl" value="" name="tgl" >																
                                                                <label for="tgl" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Jangka Waktu (Bulan)</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="jangka_waktu" value="" name="jangka_waktu" >
                                                                <label for="jangka_waktu" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-4" id="sh_pinjaman">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Margin Ditangguhkan (Rp)</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="bunga_nom_per_pa" onkeyup="formatRupiah2(this)" value="" name="bunga_nom_per_pa" placeholder="Rp">
                                                                <label for="bunga_nom_per_pa" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>                                                             
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Pokok/Plafon</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="pokok_plafon" onkeyup="formatRupiah(this)" value="" name="pokok_plafon" >
                                                                <label for="pokok_plafon" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>                                                            
                                                            <div class="col-md-6">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Angsuran Perbulan</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="angsuran_per_bulan" value="" name="angsuran_per_bulan" >
                                                                <label for="angsuran_per_bulan" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Nomer PK</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="nomer_pk" value="" name="nomer_pk" >
                                                                <label for="nomer_pk" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>
                                                            <div class="col-md-4" id="perkiraan_lawan">
                                                            <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Perkiraan Lawan</label>
                                                            @if (($LEditPerkiraan)->isEmpty())
                                                            <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                                                <div class="text-white">Data Edit Perkiraan Tidak Ada</div>
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
                                                                <input type="hidden" class="{{$data['classFormControl']}}" id="id_perkiraan" value="" name="id_perkiraan" >
                                                            @endif
                                                            <label for="rek_lawan_perk" generated="true" class="error"></label>
                                                            <label id="validationError"></label> 
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Bukti Slip Droping</label>
                                                                <input type="text" class="{{$data['classFormControl']}}" id="bukti_droping" onkeydown="upperCaseF(this)" value="" name="bukti_droping" >
                                                                <label for="bukti_droping" generated="true" class="error"></label>
                                                                <label id="validationError"></label>
                                                            </div>                                                                                                                                                     
                                                        </div>                                                                                                                   
                                                    </div>
                                                </div>                                      
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>                                                                       
                            </main>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->

<script src="{{ asset('additional/js/transaksi_admin_kredit.js?v=1.01') }}"></script>
