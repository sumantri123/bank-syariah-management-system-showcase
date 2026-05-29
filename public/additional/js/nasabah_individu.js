var data_table;

$(document).ready(function () {
    loadData();

    $('button#tambah').on('click', function () {        
//       clearModal();                 
         $('#modal_label').text('Form Tambah Data');
         $('#method_field').val("POST");
         $(".modal-form").modal('show');         
    });

    $('button#btn_simpan').on('click', function () {        
        insertUpdateProses();
    });    
        
});            

    function loadData() {
            data_table = $('#example2').DataTable({
            processing: true,
            lengthChange: false,
            initComplete: function() {
                data_table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
                $("#example2").show();
            },
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
            ajax: {
                "url": "" + base_url + '/getDataJson/nasabahIndividu',
                'type': 'GET',
                'dataType': 'JSON',
                'error': function (xhr, textStatus, ThrownException) {
                    error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
                }
            },
            columns: [
            {
                title: "Aksi",
                data: "id",
                visible: true,
                sortable: false,
                class: "text-center",
                render: function (data, type, full, meta) {
                    var result = '';
                    result += '<td class="text-center">';
                    result +=
                        '<button class="btn btn-warning btn-sm btn-edit"> <i class="bx bx-edit"></i> </button>&nbsp;';                    
                    result +=
                        '<button class="btn btn-danger  btn-sm btn-delete"> <i class="bx bx-trash"></i> </button>';
                    result += '</td>';
                    return result;
                }
            },{
                title: "No cif ",
                data: "cif",
                visible: true,
                sortable: true,
                class: "text-center"
            }, {
                title: "Nama Lengkap",
                data: "nama",                
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Tanggal Lahir",
                data: "tanggal_lahir",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Kewarganegaraan",
                data: "kewarganegaraan",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Jenis Identitas",
                data: "jenis_identitas",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "No. Identitas",
                data: "no_identitas",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Alamat",
                data: "alamat_ktp",
                visible: true,
                sortable: true,
                class: ""
            }],

            "drawCallback": function (settings) {
                $('.btn-edit').on('click', function () {
                    clearModal();
                    var data = data_table.row($(this).parents('tr')).data();
                    var dataCif = data.cif;
                    var cif = dataCif.split(".");
                    
                    $('#id').val(data.id);
                    $('#cif_1').val(cif[0]);
                    $('#cif_2').val(cif[1]);
                    $('#cif_3').val(cif[2]);
                    $('#nama_nasabah').val(data.nama);
					$('#tempat_lahir').val(data.tempat_lahir);
                    $('#tgl_lahir').val(data.tanggal_lahir);
					$('#kewarganegaraan').val(data.kewarganegaraan);					
                    $('#alamat').val(data.alamat_ktp);
					$('#kota').val(data.kota_ktp);					
                    $('#rt').val(data.rt_ktp);
					$('#rw').val(data.rw_ktp);					
                    $('#kelurahan').val(data.kelurahan_ktp);
					$('#kode_pos').val(data.kodepos_ktp);					
                    $('#telp').val(data.telepon);
					$('#id_jenis_kelamin').val(data.jenis_kelamin);					
                    $('#id_status').val(data.status);
					$('#id_pendidikan').val(data.pendidikan);					
                    $('#id_agama').val(data.agama);
					$('#id_identitas').val(data.jenis_identitas);					
                    $('#no_identitas').val(data.no_identitas);
					$('#msb_identitas').val(data.masa_berlaku);					
                    $('#kecamatan').val(data.kecamatan_ktp);
					$('#npwp').val(data.npwp);					
                    $('#alamat_domisili').val(data.alamat_domisili);
					$('#rt_domisili').val(data.rt_domisili);					
                    $('#rw_domisili').val(data.rw_domisili);
					$('#kelurahan_domisili').val(data.kelurahan_domisili);					
                    $('#kecamatan_domisili').val(data.kecamatan_domisili);
					$('#kota_domisili').val(data.kota_domisili);					
                    $('#kodepos_domisili').val(data.kodepos_domisili);
					$('#telp_domisili').val(data.telp_domisili);					
                    $('#hp_domisili').val(data.hp_domisili);
					$('#fax_domisili').val(data.fax_domisili);					
                    $('#email_domisili').val(data.email_domisili);
					$('#status_rumah_domisili').val(data.status_rumah_domisili);					
                    $('#alamat_korespondensi').val(data.alamat_korespondensi);
					$('#nm_ibu_kandung').val(data.nama_ibu_kandung);					
                    $('#nama_saudara').val(data.nama_saudara);
					$('#hubungan_saudara').val(data.hubungan_saudara);					
                    $('#kodepos_saudara').val(data.kodepos_saudara);					
                    $('#alamat_saudara').val(data.alamat_saudara);					
                    $('#kota_saudara').val(data.kota_saudara);					
                    $('#hp_saudara').val(data.hp_saudara);					
                    $('#telp_saudara').val(data.telp_saudara);					
                    $('#fax_saudara').val(data.fax_saudara);					
                    $('#pekerjaan').val(data.pekerjaan);					
                    $('#penghasilan_domisili').val(data.penghasilan);					
                    $('#nama_kantor').val(data.nama_kantor);					
                    $('#kegiatan_usaha').val(data.kegiatan_usaha);					
                    $('#alamat_kantor').val(data.alamat_kantor);					
                    $('#kota_kantor').val(data.kota_kantor);					
                    $('#kodepos_kantor').val(data.kodepos_kantor);					
                    $('#telp_kantor').val(data.telp_kantor);					
                    $('#ext_kantor').val(data.ext_kantor);					
                    $('#fax_kantor').val(data.fax_kantor);					
                    $('#jabatan_kantor').val(data.jabatan_kantor);					
                    $('#unit_kantor').val(data.unit_kantor);					
                    $('#sumber_dana').val(data.sumber_dana);					
                    $('#ahli_waris').val(data.nama_ahli_waris);					
                    $('#alamat_ahli_waris').val(data.alamat_ahli_waris);					
                    $('#traksaksi_perbulan').val(data.transaksi_tertinggi);					
                    $('#hubungan_ahli_waris').val(data.hubungan_ahli_waris);					
                    $('#kota_ahli_waris').val(data.kota_ahli_waris);					
                    $('#masuk_dhbi').val(data.masuk_dhbi);					
                    $('#tuj_buka_rekening').val(data.tujuan_buka_rekening);					
                    $('#penggunaan_dana').val(data.penggunaan_dana);					                    

                    $('#modal_label').text('Form Ubah');
                    $('#method_field').val("PUT");
                    $(".modal-form").modal('show');
                });

                $('.btn-delete').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();
                    var pass = $('#pass').val();
                    Lobibox.prompt('text', //Any input type will be valid
                    {
                        title: 'Password Kewenangan',                        
                        attrs: { 
                            placeholder: "password",
                            type: 'password',
                        },
                        callback: function ($this, type, ev) {
                            if(type=='ok'){
                                if($this.getValue()===pass){
                                    Lobibox.confirm({
                                        iconClass: true,
                                        title: 'Delete Data',                        
                                        msg: 'Yakin Hapus Nasabah"' + data.nama + '"?',
                                        callback: function ($this, type, ev) {
                                            if(type=='yes'){
                                                 deleteProses(data.id);
                                            }        
                                        }
                                    }); 
                                } else {                                    
                                    error_noti('Password Salah, Transaksi Batal');                                    
                                }        
                            }        
                        }
                    });                    
                });
            }
		});            
    }

    function deleteProses(id) {
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/delete/nasabahIndividu/" + id,
            dataType: 'JSON',            

            success: function (data) {
                var msg = (data.msg!="") ? data.msg:"";
	            if (data.status == 'delete_successful') {
	                success_noti('Data Berhasil Terhapus');
	                data_table.ajax.reload(null, false);
	            } else if (data.status == 'delete_failed') {
	                error_noti('Data Gagal Dihapus, ' +msg);
	            } else {
                    error_noti('Data Gagal Dihapus (Kesalahan Sistem)');
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
            }
        });
	}

    function insertUpdateProses() {

        var form = $('#form_nasabah');
        if (form.valid() == true) {
            
            var method = $('#method_field').val();
            var action_url = "" + base_url + "/nasabahIndividu";            
            var action_type = "Tambah";
            if (method === "PUT") {
                action_url = "" + base_url + "/nasabahIndividu/" + $('#id').val();
                action_type = "Ubah";
            }

            $.ajax({
                type: 'POST',
                url: action_url,
                dataType: 'JSON',
                data: form.serialize(),                

                success: function (data) {
                    if (data.status == 'insert_successful') {
                        success_noti('Berhasil ' + action_type + ' Data');
                        $('.modal-form').modal('toggle');            
                        clearModal();                                  
                        data_table.ajax.reload(null, false);
                    } else if (data.status == 'insert_failed') {
                        error_noti('Gagal ' + action_type + ' Data, '+ data.msg);
                        
                        var errors = data.error;
                        errorValidationLaravel(errors, '#error-validation');


                    } else {
                        error_noti('Gagal ' + action_type + ' (Kesalahan Sistem)');
                    }
                },

                error: function (xmlhttprequest, textstatus, message) {
                    error_noti('Koneksi Ke Server Gagal, '+message);
                }

            });            
            //sweetAlertLoading('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah',1000);
        } else {                                    
            error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
        }
    }

    var validator = $('#form_nasabah').validate({

        rules: {
            //cif_1: {required: true},
            //cif_2: {required: true, number: true},
            //cif_3: {required: true},
            id_identitas: {required: true},
            no_identitas: {required: true},
            msb_identitas: {required: true},
            nama_nasabah: {required: true},
            id_jenis_kelamin: {required: true},
            id_agama: {required: true},
            tempat_lahir: {required: true},
            tgl_lahir: {required: true},
            id_status: {required: true},
            kewarganegaraan: {required: true},
            id_pendidikan: {required: true},
            npwp: {required: true},
            alamat: {required: true},
            kota: {required: true},
            rt: {required: true, number: true},
            rw: {required: true, number: true},
            kelurahan: {required: true},
            kecamatan: {required: true},
            kode_pos: {required: true, number: true},
            telp: {required: true, number: true},

            // alamat_domisili: {required: true},
            // rt_domisili: {required: true, number: true},
            // rw_domisili: {required: true, number: true},
            // kelurahan_domisili: {required: true},
            // kecamatan_domisili: {required: true},
            // kota_domisili: {required: true},
            // kodepos_domisili: {required: true},
            // telp_domisili: {required: true, number: true},
            // hp_domisili: {required: true, number: true},
            // fax_domisili: {required: true, number: true},
            // email_domisili: {required: true},
            // status_rumah_domisili: {required: true},
            // alamat_korespondensi: {required: true},
            // nm_ibu_kandung: {required: true},
            // nama_saudara: {required: true},
            // hubungan_saudara: {required: true},
            // kodepos_saudara: {required: true},
            // alamat_saudara: {required: true},
            // kota_saudara: {required: true},
            // hp_saudara: {required: true, number: true},
            // telp_saudara: {required: true, number: true},
            // fax_saudara: {required: true, number: true},
            // pekerjaan: {required: true},
            // penghasilan_domisili: {required: true},
            // nama_kantor: {required: true},
            // kegiatan_usaha: {required: true},
            // alamat_kantor: {required: true},
            // kota_kantor: {required: true},
            // kodepos_kantor: {required: true, number: true},
            // telp_kantor: {required: true, number: true},
            // ext_kantor: {required: true, number: true},
            // fax_kantor: {required: true, number: true},
            // jabatan_kantor: {required: true},
            // unit_kantor: {required: true},
            // sumber_dana: {required: true},
            // ahli_waris: {required: true},
            // alamat_ahli_waris: {required: true},
            // traksaksi_perbulan: {required: true, number: true},
            // hubungan_ahli_waris: {required: true},
            // kota_ahli_waris: {required: true},
            // masuk_dhbi: {required: true},
            // tuj_buka_rekening: {required: true},
            // penggunaan_dana: {required: true}
        },

        highlight: function (element, errorClass, validClass, error) {

            $(element.form).find("[id=" + element.id + "]").addClass('is-invalid');
            $(element.form).find("[id=" + element.id + "]").addClass('is-invalid');
            $(element.form).find("[id=" + element.id + "]").removeClass('is-valid');

        },

        unhighlight: function (element, errorClass, validClass) {
            $(element.form).find("[id=" + element.id + "]").removeClass('is-invalid');
            $(element.form).find("[id=" + element.id + "]").addClass('is-valid');
        }
    });

//--------------------- Setup DatePicker ---------------------
$('.single-select').select2({
    theme: 'bootstrap4',		
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
    allowClear: Boolean($(this).data('allow-clear')),
});

$('.single-select2').select2({
    theme: 'bootstrap4',		
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
    allowClear: Boolean($(this).data('allow-clear')),
});

$('.datepicker').pickadate({			
        selectMonths: true,
        selectYears: true
    }),		

$('.timepicker').pickatime()

$(function() {
    $(".knob").knob();
});

$(function () {
    $('#date-time').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD HH:mm'
    });
    $('#date').bootstrapMaterialDatePicker({
        time: false
    });
    $('#time').bootstrapMaterialDatePicker({
        date: false,
        format: 'HH:mm'
    });
});    