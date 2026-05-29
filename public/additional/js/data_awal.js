var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {    
    loadData();            
});    

$('.btn-download-template').on('click', function () {
    window.open(base_url+"/template/data_awal_syariah.xlsx", "_blank"); 
});

$('INPUT[type="file"]').change(function () {
    var ext = this.value.match(/\.(.+)$/)[1];

    if(this.files[0].size > 2000000) {            

        error_noti('Please upload file less than 2MB. Thanks!!');            
        $(this).val('');

      } else {

        switch (ext) {
            case 'xls':
            case 'xlsx':        
                $('#btnUpload').attr('disabled', false);
                break;
            default:
                error_noti('File Yang Diperbolehkan Hanya Extension xls / xlsx');            
                this.value = '';
        }
        
      }    
});

$('#form_upload').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);        
    var form = $('#form_upload');

    if (form.valid() == true) {    
        
        $.ajax({
            type:'POST',
            url: "" + base_url + "/uploadData",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                BeforeSend();
            },
            complete: function(){
                AfterSend();
            },
            success: (data) => {
                // $("#loading").hide();  				
                this.reset();
                success_noti('Data Berhasil Diupload ');   
                $('#example2').DataTable().destroy(); 
                loadData();                                    
                //$('#data-table-combine').DataTable().ajax.reload(null, false);
            },
            error: function (error) {
                //error_noti(error.responseJSON.errors.file);
                error_noti("Data Gagal Diupload");
            }
        });
    } else {
        
        error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
    }    
});

function downloadData(path,name) {   
    
    window.open(base_url+"/excel/"+name, "_blank");    
};

function loadData() {
        data_table = $('#example2').DataTable({
        processing: false,
        lengthChange: true,        
        ajax: {
            "url": "" + base_url + '/getDataJson/data_sa_file',
            'type': 'GET',
            'dataType': 'JSON',
            'error': function (xhr, textStatus, ThrownException) {
                error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
            }
        },
        columns: [
        {
            title: "Nama File ",
            data: "file_name",
            visible: true,
            sortable: true,
            class: ""
        }, {
            title: "Upload When",
            data: "dt_record",                
            visible: true,
            sortable: true,
            class: ""
        },{
            title: "Total Data",
            data: "total_data",                
            visible: true,
            sortable: true,
            class: "text-center"
        },{
            title: "Create Saldo Awal",
            data: "file_id",
            visible: true,
            sortable: false,
            class: "text-center",
            render: function (data, type, full, meta) {
                var result = '';
                result += '<td class="text-center">';
                
                result += '<button type="button" title="Buat Saldo Awal" class="btn btn-outline-primary btn-sm btn-action px-2 ms-2 btn-tab" ><i class="bx bx-refresh me-0"></i>Tab. Wadiah</button>';                                    
                result += '<button type="button" title="Buat Saldo Awal" class="btn btn-outline-primary btn-sm btn-action px-2 ms-2 btn-tab2" ><i class="bx bx-refresh me-0"></i>Tab. Mudharabah</button>';
				result += '<button type="button" title="Buat Saldo Awal" class="btn btn-outline-primary btn-sm btn-action px-2 ms-2 btn-giro" ><i class="bx bx-refresh me-0"></i>Giro Wadiah</button><br><br>';                                    
                result += '<button type="button" title="Buat Saldo Awal" class="btn btn-outline-primary btn-sm btn-action px-2 ms-2 btn-dep" ><i class="bx bx-refresh me-0"></i>Dep. Mudharabah 1 Bln</button>';
				result += '<button type="button" title="Buat Saldo Awal" class="btn btn-outline-primary btn-sm btn-action px-2 ms-2 btn-pin-mh" ><i class="bx bx-refresh me-0"></i>Pinj. Murabahah</button>';
				result += '<button type="button" title="Buat Saldo Awal" class="btn btn-outline-primary btn-sm btn-action px-2 ms-2 btn-pin-md" ><i class="bx bx-refresh me-0"></i>Pinj. Mudharabah</button>';
               // result += '<button type="button" title="Buat Saldo Awal" class="btn btn-outline-primary btn-sm btn-action px-2 ms-2 btn-pin" ><i class="bx bx-refresh me-0"></i>Pinjaman</button>';                                                    
                
                result += '</td>';
                return result;
            }
        }],

        "drawCallback": function (settings) {
            $('.btn-tab').on('click', function () {
                var data = data_table.row($(this).parents('tr')).data();                 
                Lobibox.confirm({
                    iconClass: true,
                    title: 'Saldo Awal',                        
                    msg: 'Yakin Buat Saldo Awal Rekening Tabungan ?',
                    callback: function ($this, type, ev) {
                        if(type=='yes'){
                            rekTab(data.file_id);
                        }        
                    }
                });                                                                
            });

	   $('.btn-tab2').on('click', function () {
                var data = data_table.row($(this).parents('tr')).data();
                Lobibox.confirm({
                    iconClass: true,
                    title: 'Saldo Awal',
                    msg: 'Yakin Buat Saldo Awal Rekening Tabungan ?',
                    callback: function ($this, type, ev) {
                        if(type=='yes'){
                            rekTabMud(data.file_id);
                        }
                    }
                });
            });


            $('.btn-giro').on('click', function () {
                var data = data_table.row($(this).parents('tr')).data();
                Lobibox.confirm({
                    iconClass: true,
                    title: 'Saldo Awal',                        
                    msg: 'Yakin Buat Saldo Awal Rekening Giro ?',
                    callback: function ($this, type, ev) {
                        if(type=='yes'){
                            rekGiro(data.file_id);
                        }        
                    }
                });                                                                
            });

            $('.btn-dep').on('click', function () {
                var data = data_table.row($(this).parents('tr')).data();           
                Lobibox.confirm({
                    iconClass: true,
                    title: 'Saldo Awal',                        
                    msg: 'Yakin Buat Saldo Awal Rekening Deposito ?',
                    callback: function ($this, type, ev) {
                        if(type=='yes'){
                            rekDep(data.file_id);
                        }        
                    }
                });                                                                     
            });

            $('.btn-pin-mh').on('click', function () {
                var data = data_table.row($(this).parents('tr')).data();                
                Lobibox.confirm({
                    iconClass: true,
                    title: 'Saldo Awal',                        
                    msg: 'Yakin Buat Saldo Awal Rekening Pinjaman Muharabah?',
                    callback: function ($this, type, ev) {
                        if(type=='yes'){
                            rekPinMh(data.file_id);
                        }        
                    }
                });                                                                
            });
			
			$('.btn-pin-md').on('click', function () {
                var data = data_table.row($(this).parents('tr')).data();                
                Lobibox.confirm({
                    iconClass: true,
                    title: 'Saldo Awal',                        
                    msg: 'Yakin Buat Saldo Awal Rekening Pinjaman Mudharabah?',
                    callback: function ($this, type, ev) {
                        if(type=='yes'){
                            rekPinMd(data.file_id);
                        }        
                    }
                });                                                                
            });
        }
	});            
}    

function rekTab(id) {   
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/createRekTab/" + id,
        dataType: 'JSON',        
        beforeSend: function(){
            BeforeSend();
        },
        complete: function(){
            AfterSend();
        },
        success: function (data) {
            if (data.status == 'insert_successful') {
                success_noti('Data Berhasil Disimpan');
                disableClearEntry();                                
            } else if (data.status == 'insert_failed') {
                error_noti('Data Gagal Disimpan');                
            } else if (data.status == 'insert_failed2') {
                error_noti(data.msg);                
            } else {
                error_noti('Data Gagal Disimpan (Kesalahan Sistem)');                
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });    
};

function rekTabMud(id) {
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/createRekTabMud/" + id,
        dataType: 'JSON',
        beforeSend: function(){
            BeforeSend();
        },
        complete: function(){
            AfterSend();
        },
        success: function (data) {
            if (data.status == 'insert_successful') {
                success_noti('Data Berhasil Disimpan');
                disableClearEntry();
            } else if (data.status == 'insert_failed') {
                error_noti('Data Gagal Disimpan');
            } else if (data.status == 'insert_failed2') {
                error_noti(data.msg);
            } else {
                error_noti('Data Gagal Disimpan (Kesalahan Sistem)');
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });
};


function rekGiro(id) {   
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/createRekGiro/" + id,
        dataType: 'JSON',        
        beforeSend: function(){
            BeforeSend();
        },
        complete: function(){
            AfterSend();
        },
        success: function (data) {
            if (data.status == 'insert_successful') {
                success_noti('Data Berhasil Disimpan');
                disableClearEntry();                                
            } else if (data.status == 'insert_failed') {
                error_noti('Data Gagal Disimpan');                
            } else if (data.status == 'insert_failed2') {
                error_noti(data.msg);                
            } else {
                error_noti('Data Gagal Disimpan (Kesalahan Sistem)');                
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });   
};

function rekDep(id) {   
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/createRekDep/" + id,
        dataType: 'JSON',        
        beforeSend: function(){
            BeforeSend();
        },
        complete: function(){
            AfterSend();
        },
        success: function (data) {
            if (data.status == 'insert_successful') {
                success_noti('Data Berhasil Disimpan');
                disableClearEntry();                                
            } else if (data.status == 'insert_failed') {
                error_noti('Data Gagal Disimpan');                
            } else if (data.status == 'insert_failed2') {
                error_noti(data.msg);                
            } else {
                error_noti('Data Gagal Disimpan (Kesalahan Sistem)');                
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });      
};

function rekPinMh(id) {  	
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/createRekPinMh/" + id,
        dataType: 'JSON',        
        beforeSend: function(){
            BeforeSend();
        },
        complete: function(){
            AfterSend();
        },
        success: function (data) {
            if (data.status == 'insert_successful') {
                success_noti('Data Berhasil Disimpan');
                disableClearEntry();                                
            } else if (data.status == 'insert_failed') {
                error_noti('Data Gagal Disimpan');                
            } else if (data.status == 'insert_failed2') {
                error_noti(data.msg);                
            } else {
                error_noti('Data Gagal Disimpan (Kesalahan Sistem)');                
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });     
};

function rekPinMd(id) {   
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/createRekPinMd/" + id,
        dataType: 'JSON',        
        beforeSend: function(){
            BeforeSend();
        },
        complete: function(){
            AfterSend();
        },
        success: function (data) {
            if (data.status == 'insert_successful') {
                success_noti('Data Berhasil Disimpan');
                disableClearEntry();                                
            } else if (data.status == 'insert_failed') {
                error_noti('Data Gagal Disimpan');                
            } else if (data.status == 'insert_failed2') {
                error_noti(data.msg);                
            } else {
                error_noti('Data Gagal Disimpan (Kesalahan Sistem)');                
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });     
};

var validator = $('#form_upload').validate({

    rules: {
        file: {required: true}        
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
