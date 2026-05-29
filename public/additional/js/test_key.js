var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {        
    disableEntry();
    $('#searchGrup').hide("slow");
    $('#tgl').attr('disabled', true);    
});    

$('button#btn_search').on('click', function () {                          
    disableClearEntry();
    $('#searchGrup').show("slow");
    $('#search').attr('readOnly', false);
    $('#search').focus();    
    $('#tgl').attr('disabled', true); 
    $("#cabang_pengirim").select2().select2('val','""');
    $("#cabang_penerima").select2().select2('val','""');                    
    $("#cabang_pengirim").select2({ width: "100%" });    
    $("#cabang_penerima").select2({ width: "100%" });           
    $('#id_transfer').val("");
    $("#hasilKeterangan").text("");
    
});

$('button#btn_new').on('click', function () {  
    
    enableEntry();  
    // var today = new Date();
    // var dd = String(today.getDate()).padStart(2, '0');
    // var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    // var yyyy = today.getFullYear();
    // today = yyyy + '-' + mm + '-' + dd;    
    $('#no_bukti').focus();    
    $('#tgl').attr('disabled', false);    
    // $('#tgl').val(today);      
    $("#cabang_pengirim").select2().select2('val','""');
    $("#cabang_penerima").select2().select2('val','""');                    
    $("#cabang_pengirim").select2({ width: "100%" });    
    $("#cabang_penerima").select2({ width: "100%" });    
    $('#id_transfer').val("");
    $("#hasilKeterangan").text("");
    $('#method_field').val("POST");      
    $('#search').attr('readOnly', true); 
    $('#searchGrup').hide("slow"); 
    $('#test_key_ke').attr('readOnly', true);      
    // totalDebetKredit();    
    
});

$('button#btn_simpan').on('click', function () {   
    var method = $('#method_field').val();   
    if(method==="SEARCH") {
        info_noti('Data Tidak Boleh DiEdit/Disimpan');	                        
    } else {
        insertUpdateInduk();
    }        
}); 

$('button#btn_delete').on('click', function () {                
    var id = document.getElementById("idTr").value;
    var no_bukti = document.getElementById("no_bukti").value;
    if(id==""){
        info_noti('Silahkan Simpan Data Anda Terlebih Dahulu');	                 
        //sweetAlertDefault('<b>Silahkan Simpan Data Anda Terlebih Dahulu </b>', 'error', 2000 );
    } else {         
        Lobibox.confirm({
            iconClass: true,
            title: 'Delete Data',                        
            msg: 'Yakin Hapus Kode Bukti : "' + no_bukti + '" ?',            
            callback: function ($this, type, ev) {
                if(type=='yes'){
                    deleteProses(id); 
                }        
            }
        });         
    }
});

$("#search").keypress(function (e) {
    if(e.keyCode==13){
        var kode = $('#search').val();        
        $('#search').attr('readOnly', true);
        $('#method_field').val("SEARCH");    
        
        $.ajax({
            type: 'post',
            url: "" + base_url + "/search/testKey",
            dataType: 'JSON',
            data: {
                _token: CSRF_TOKEN,                
                kode: kode
            },
            beforeSend: function(){
                BeforeSend();
            },
            complete: function(){
                AfterSend();
            },
            success: function (data) {
                if (data.status == 'oke') {
                    
                    $('#no_bukti').val(data.jbNo);
                    $('#idTr').val(data.jbId);
                    $('#tgl').val(data.jbTgl);
                    $("#cabang_pengirim").select2().select2('val',''+data.jbCbngPengirim+'');
                    $("#cabang_pengirim").select2({ width: "100%" });                                                            
                    $("#cabang_penerima").select2().select2('val',''+data.jbCbngPenerima+'');
                    $("#cabang_penerima").select2({ width: "100%" });
                    $('#nominal').val(convertToRupiahNoRp(data.jbNominal));
                    $('#id_transfer').val(data.jbIdTransfer);
                    $('#test_key_ke').val(data.jbTestKeyKe);
                    $('#hasil_test_key').val(data.jbTestKeyNilai);
                    $("#hasilKeterangan").text("SESUAI");
                
                } else {
                    error_noti('Data Tidak Tersedia');                     
                }
            },
    
            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
            }
        });
    }
});

function getval0(sel){                
    var selected = sel.value;        

    if(selected !=""){
        $('#kode_transfer').val(selected);  	                        
    }
}

function getval1(sel){                
    var selected = sel.value;

    if(selected !=""){
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/GetPengirim/" + selected,
            dataType: 'JSON',            
            success: function (data) {
                if (data.status == 'oke') {                    
                    $('#kode_pengirim').val(data.sandiPengirim);  	                                    
                } else if (data.status == 'null') {
                    info_noti('Tidak Ada Data Sandi Pengirim');	                
                } else {
                    error_noti('Gagal (Kesalahan Sistem)');                     
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');                 
            }
        });
    }
}

function getval2(sel){                
    var selected = sel.value;

    if(selected !=""){
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/GetPenerima/" + selected,
            dataType: 'JSON',            
            success: function (data) {
                if (data.status == 'oke') {                    
                    $('#kode_penerima').val(data.sandiPenerima);
                } else if (data.status == 'null') {
                    info_noti('Tidak Ada Data Sandi Penerima');	                
                } else {
                    error_noti('Gagal (Kesalahan Sistem)');                     
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');                 
            }
        });
    }
}

function getval3(sel){                
    var selected = sel.value;

    if(selected !=""){
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/GetTglBulan/" + selected,
            dataType: 'JSON',            
            success: function (data) {
                if (data.status == 'oke') {                    
                    $('#kode_tanggal').val(data.sandiTanggal);
                    $('#kode_bulan').val(data.sandiBulan);
                } else if (data.status == 'null') {
                    info_noti('Tidak Ada Data Sandi');	                
                } else {
                    error_noti('Gagal (Kesalahan Sistem)');                     
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');                 
            }
        });
    }
}

function formatRupiah(y){
    var selected = y.value;    

    $( "#nominal" ).on('keyup',(function (event) {
        $(this).val(function (index, value) {
            return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });        

    }));    
    
}

function totalSandi(y){

    var selected = y.value;
    var total = selected.length;
    var sandiTgl = document.getElementById("kode_tanggal").value;
    var sandiBln = document.getElementById("kode_bulan").value;
    var sandiPenerima = document.getElementById("kode_penerima").value;
    var sandiPengirim = document.getElementById("kode_pengirim").value;
    var nominal = document.getElementById("nominal").value;

    if(sandiTgl=="" || sandiBln=="" || sandiPenerima =="" || sandiPengirim == "" || nominal==""){
        
        info_noti('Inputan Masih Ada Yang Kosong');	                

    } else if(total == 4) {
        
        $.ajax({
            type: 'post',
            url: "" + base_url + "/GetNominal",
            dataType: 'JSON',
            data: {
                _token: CSRF_TOKEN,
                nominal: nominal                
            },                        
            success: function (data) {
                if (data.status == 'oke') {   
                    var totalSandi = (parseInt(sandiTgl) + parseInt(sandiBln) + parseInt(sandiPenerima) + parseInt(sandiPengirim) + parseInt(data.sandiNominal));
                    var hasil = parseInt(selected) - parseInt(totalSandi);                    

                    if(hasil == 0){
                        $("#hasilKeterangan").text("SESUAI");
                        $('#btn_simpan').attr('disabled',false);
                    } else {
                        $("#hasilKeterangan").text("TIDAK SESUAI");
                        $('#btn_simpan').attr('disabled',true);
                    }                    
                } else if (data.status == 'null') {
                    info_noti('Tidak Ada Data Sandi');	                
                } else {
                    error_noti('Gagal (Kesalahan Sistem)');                     
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                //error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');                 
            }
        });

    } 
}

function deleteProses(id) {        

    $.ajax({
        type: 'GET',
        url: "" + base_url + "/delete/testKey/" + id,
        dataType: 'JSON',        
        beforeSend: function(){
            BeforeSend();
        },
        complete: function(){
            AfterSend();
        },
        success: function (data) {
            if (data.status == 'delete_successful') {
                success_noti('Data Berhasil Terhapus');            
                $('#idTr').val("");
                $("#cabang_pengirim").select2().select2('val','""');
                $("#cabang_penerima").select2().select2('val','""');                    
                $("#cabang_pengirim").select2({ width: "100%" });    
                $("#cabang_penerima").select2({ width: "100%" });     
                $('#id_transfer').val(""); 
                disableClearEntry();                                
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

function insertUpdateInduk() {

    var form = $('#formEntry');   
    var keterangan = $('#hasilKeterangan').text();

    if(keterangan=="SESUAI"){

        if (form.valid() == true) {

            var method = $('#method_field').val();                    
            var action_url = "" + base_url + "/testKeySave";  
            var action_type = "Tambah";                      
            if (method === "PUT") {
                action_url = "" + base_url + "/testKeyUpdate/" + $('#idTr').val();
                action_type = "Ubah";
            }
    
            $.ajax({
                type: 'POST',
                url: action_url,
                dataType: 'JSON',
                data: form.serialize(),            
                beforeSend: function(){
                    BeforeSend();
                },
                complete: function(){
                    AfterSend();
                },
                success: function (data) {                    
                    if (data.status == 'insert_successful') {                        
                        success_noti('Berhasil ' + action_type + ' Data');
                        $('#tgl').attr('disabled', true); 
                        $('#idTr').val(data.id);                    
                        $('#method_field').val("PUT");
                        disableEntry();                                                                                
                                                                                
                    } else if (data.status == 'insert_failed') {
                        
                        error_noti('Gagal ' + action_type + ' Data'+ data.msg);                             
    
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

    } else {
        error_noti('Hasil Belum Sesuai, Silahkan Cek Ulang');
    } 
}

var validator = $('#formEntry').validate({

    rules: {    
        no_bukti: {required: true},    
        cabang_penerima: {required: true},
        cabang_pengirim: {required: true},
        id_transfer: {required: true},
        nominal: {required: true, number: true},
        tgl: {required: true},        
        id_kode_transaksi: {required: true},
        keterangan: {required: true},        
        hasil_test_key: {required: true},
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