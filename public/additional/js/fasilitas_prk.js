var data_table;

$(document).ready(function () {
    loadData();        

    $('button#tambah').on('click', function () {  
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
                        clearModal();     
                        $('#modal_label').text('Form Tambah Data');
                        $('#method_field').val("POST");
                        $(".modal-form").modal('show');
                    } else {                                    
                        error_noti('Password Salah, Transaksi Batal');                                    
                    }        
                }        
            }
        }); 
    });

    $('button#btn_simpan').on('click', function () {        
        insertUpdateProses();
    });    
        
});    

    function getval(sel)
    {                
        var selected = sel.value;
        
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/nasabahGiroGetData/" + selected,
            dataType: 'JSON',            
            success: function (data) {
	            if (data.status == 'oke') {                    
	                $('#nama_nasabah').val(data.nama);  	                
                    $('#cif').val(data.cif);  	                
                    $('#tgl_buka').val(data.tgl_buka);  	                
                    $('#id_rek').val(data.id_rek);  	                
	            } else if (data.status == 'null') {
                    info_noti('Tidak Ada Data Nasabah Giro Rupiah');	                
	            } else {
                    error_noti('Gagal (Kesalahan Sistem)');                     
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');                 
            }
        });        
    }

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
                "url": "" + base_url + '/getDataJson/fasilitas_prk_data',
                'type': 'GET',
                'dataType': 'JSON',
                'error': function (xhr, textStatus, ThrownException) {
                    error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
                }
            },
            columns: [
            {
                title: "Aksi",
                data: "tab_id",
                visible: true,
                sortable: false,
                class: "text-center",
                render: function (data, type, full, meta) {
                    var result = '';
                    result += '<td class="text-center">';                    
                    result +=
                        '<button class="btn btn-danger  btn-sm btn-delete"> <i class="bx bx-power-off"></i> </button>';
                    result += '</td>';
                    return result;
                }
            },{
                title: "No Rekening ",
                data: "nomor_rekening",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Customer Number",
                data: "cif",                
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Nama Lengkap",
                data: "nama",                
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Tanggal Buka",
                data: "tanggal_buka",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Plafon PRK",
                data: "prk_nominal",
                visible: true,
                sortable: true,
                class: "",
                render: function (data, type, row) {
                    if (row.prk_nominal === 0) {
                        return '0';
                    } else {                                    
                        return "<span style='float: right'>"+convertToRupiahNoRp(row.prk_nominal)+"</span>";
                    }
                }
            }],

            "drawCallback": function (settings) {                
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
                                        msg: 'Yakin Hapus Plafon PRK"' + data.nama + '"?',
                                        callback: function ($this, type, ev) {
                                            if(type=='yes'){
                                                deleteProses(data.tab_id);
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
            url: "" + base_url + "/statusFasilitasPrk/" + id,
            dataType: 'JSON',            

            success: function (data) {
	            if (data.status == 'insert_successful') {
	                success_noti('Plafon PRK Berhasil Dihapus');
	                data_table.ajax.reload(null, false);
	            } else if (data.status == 'insert_failed') {
	                error_noti('Plafon PRK Gagal Dihapus');
	            } else {
                    error_noti('Plafon PRK Gagal Dihapus (Kesalahan Sistem)');
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
                        
            var action_url = "" + base_url + "/fasilitasPrk";            
            var action_type = "Tambah";            

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
                        //alert(data.error.bunga);
                        error_noti(data.msg);                        
                       
                        var errors = data.error;
                        // $.each(errors, function( index, value ) {
                        //     $("input[name='"+index+"']" ).css('border-color: #a94442;');
                        //     $("input[name='"+index+"']" ).parent().append(value[0]);
                        //   });
                                                                        
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

    function formatRupiah(y){
        var query = y.value;
        
        $("#plafon_prk").on('keyup',(function (event) {
            $(this).val(function (index, value) {
                return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            });
        }));
    }
    
    var validator = $('#form_nasabah').validate({

        rules: {
            tgl_buka: {required: true},
            // no_rekening_2: {required: true, number: true},
            cif: {required: true},
            nama_nasabah: {required: true},
            plafon_prk: {required: true, number: true},          
            // bunga: {number: true},
            // sandi_pemilik: {required: true},                                    
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