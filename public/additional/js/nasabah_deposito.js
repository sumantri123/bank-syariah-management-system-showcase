var data_table;

$(document).ready(function () {
    loadData();    
    

    $('button#tambah').on('click', function () {        
        clearModal();         
        getval($("#jenis_deposito"));
        $("#customer_number").select2().select2('val','');
        $('#customer_number').attr('disabled', false);        
        $('#jangka_deposito').attr('disabled', false); 
        $('#bunga').attr('disabled', false);       
        $('#tgl').attr('disabled', false);        
        $('#sandi_pemilik').attr('disabled', false);
        $('#modal_label').text('Form Tambah Data');
        $('#method_field').val("POST");
        $(".modal-form").modal('show');
    });

    $('button#btn_simpan').on('click', function () {        
        insertUpdateProses();
    });    
        
});    

    function getval(sel)
    {                
        var selected = sel.value;        
        var kode = (selected=="Valas") ? "303005":"";
        $('#no_rekening_1').val(kode); 
        $('#jangka_deposito').attr('disabled', true);
        $('#jangka_deposito').val("");          

        if(selected=="Valas"){                                    

            $.ajax({
                type: 'GET',
                url: "" + base_url + "/nasabahDepositoGetPerkiraan/" + kode,
                dataType: 'JSON',            
                success: function (data) {
                    if (data.status == 'oke') {                    
                        $('#id_perkiraan').val(data.data);  	                
                    } else if (data.status == 'null') {
                        info_noti('Master Perkiraan Deposito Belum Ditambahkan');	                
                    } else {
                        error_noti('Gagal (Kesalahan Sistem)');                     
                    }
                },
    
                error: function (xmlhttprequest, textstatus, message) {
                    error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
                }
            });
        } else {
            $('#id_perkiraan').val("");
            $('#jangka_deposito').attr('disabled', false);             
        }        
    }

    function getvaljangka(sel)
    {                
        var selected = sel.value;
        if(selected=="1"){
            kode = "216.002";            
        } else if(selected=="3"){
            kode = "216.003";            
        } else if(selected=="4"){
            kode = "216.004";            
        } else if(selected=="12"){
            kode = "216.005";            
        } else {

        }
        

        $.ajax({
            type: 'GET',
            url: "" + base_url + "/nasabahDepositoDepPerkiraanDep/" + kode,
            dataType: 'JSON',            
            success: function (data) {
                if (data.status == 'oke') {                           
                    $('#id_perkiraan').val(data.data);
                    $('#no_rekening_1').val(data.kode);
                } else if (data.status == 'null') {
                    info_noti('Master Perkiraan Deposito Belum Ditambahkan');	                
                } else {
                    //sweetAlertDefault('<b>Gagal (Kesalahan Sistem)</b>', 'error', 2000 );
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman' +message);
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
                "url": "" + base_url + '/getDataJson/nasabahDeposito',
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
                        '<button class="btn btn-warning btn-sm btn-edit"> <i class="bx bx-edit"></i> </button>&nbsp;';                    
                    result +=
                        '<button class="btn btn-danger  btn-sm btn-delete"> <i class="bx bx-trash"></i> </button>';
                    result += '</td>';
                    return result;
                }
            },{
                title: "No Rekening ",
                data: "nomor_rekening",
                visible: true,
                sortable: true,
                class: "text-center"
            }, {
                title: "Jenis Deposito",
                data: "jenis_pembayaran",                
                visible: true,
                sortable: true,
                class: "text-center"
            },{
                title: "Jangka",
                data: "jangka",
                visible: true,
                sortable: true,
                class: "text-center"
            },{
                title: "Margin",
                data: "bunga",                
                visible: true,
                sortable: true,
                class: "text-center"
            },{
                title: "Nama Lengkap",
                data: "nama",                
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Alamat",
                data: "alamat_ktp",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Kota",
                data: "kota_ktp",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "No. Id",
                data: "no_identitas",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Bidang Usaha",
                data: "kegiatan_usaha",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Alamat Kantor",
                data: "alamat_kantor",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Kota Kantor",
                data: "kota_kantor",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Telp",
                data: "telp_kantor",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Tgl Buka",
                data: "tanggal_buka",
                visible: true,
                sortable: true,
                class: ""
            }],

            "drawCallback": function (settings) {
                $('.btn-edit').on('click', function () {
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

                                    clearModal();                    
                                    var dataNoRek = data.nomor_rekening;
                                    var noRek = dataNoRek.split(".");
                                    
                                    $('#id').val(data.tab_id);
                                    $('#no_rekening_1').val(noRek[0]);
                                    $('#no_rekening_2').val(noRek[1]);
                                    $('#no_rekening_2').attr('readonly', true);
                                    $("#customer_number").select2().select2('val',''+data.id_nasabah+'');
                                    $('#customer_number').attr('disabled', true);
                                    $('#jenis_deposito').val(data.jenis_pembayaran);
                                    $('#jenis_deposito').attr('disabled', true);
                                    $('#jangka_deposito').val(data.jangka);
                                    $('#jangka_deposito').attr('disabled', true);
                                    $('#tgl').val(data.tanggal_buka);
                                    $('#tgl').attr('disabled', true);
                                    $('#bunga').val(data.bunga);
                                    $('#bunga').attr('disabled', true);
                                    $('#sandi_pemilik').val(data.sandi_pemilik);
                                    $('#sandi_pemilik').attr('disabled', true);
                                                        
                                    $('#modal_label').text('Form Ubah');
                                    $('#method_field').val("PUT");
                                    $(".modal-form").modal('show');
                                    
                                } else {                                    
                                    error_noti('Password Salah, Transaksi Batal');                                    
                                }        
                            }        
                        }
                    });                     
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
                                        msg: 'Yakin Hapus Rekening Deposito "' + data.nama + '"?',
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
            url: "" + base_url + "/delete/nasabahDeposito/" + id,
            dataType: 'JSON',            

            success: function (data) {
	            if (data.status == 'delete_successful') {
	                success_noti('Data Berhasil Terhapus');
	                data_table.ajax.reload(null, false);
	            } else if (data.status == 'delete_failed') {
	                error_noti('Data Gagal Dihapus');
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
            var action_url = "" + base_url + "/nasabahDeposito";            
            var action_type = "Tambah";
            if (method === "PUT") {
                action_url = "" + base_url + "/nasabahDeposito/" + $('#id').val();
                action_type = "Ubah";
            }

            $.ajax({
                type: 'POST',
                url: action_url,
                dataType: 'JSON',
                data: form.serialize(),                

                success: function (data) {
                    if (data.status == 'insert_successful') {
                        success_noti('Berhasil ' + action_type + ' Data <br>' +data.msg);
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
            // no_rekening_1: {required: true},
            // no_rekening_2: {required: true, number: true},
            id_perkiraan: {required: true},
            customer_number: {required: true},
            jangka_deposito: {required: true},
            jenis_deposito: {required: true},
            tgl: {required: true},            
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
