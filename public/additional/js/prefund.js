var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {        
    $('#searchGrup').hide("slow");
    disableEntry();

});    

$('button#btn_search').on('click', function () {                      
    $('#searchGrup').show("slow");
    disableClearEntry();    
    $('#search').attr('readOnly', false);
    $('#search').focus();    
    clearSelect2();        
});

$('button#btn_new').on('click', function () {  

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;

    enableEntry();          
    $('#tgl').val(today);      
    $('#tgl').attr('readOnly', true);        
    $('#method_field').val("POST");  
    $('#search').attr('readOnly', true);
    $('#searchGrup').hide("slow");     
    $('#no_bukti').focus();    
    clearSelect2();        
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

    var id = document.getElementById("id_jb").value;
    var idRtgs = document.getElementById("id_rtgs").value;   

    var no_bukti = document.getElementById("no_bukti").value;
    if(id==""){
        info_noti('Silahkan Simpan Data Anda Terlebih Dahulu');	                 
        //sweetAlertDefault('<b>Silahkan Simpan Data Anda Terlebih Dahulu </b>', 'error', 2000 );
    } else {           
        Lobibox.confirm({
            iconClass: true,
            title: 'Delete Data',                        
            msg: 'Yakin Hapus No. Bukti : "' + no_bukti + '" ?',            
            callback: function ($this, type, ev) {
                if(type=='yes'){
                    deleteProses(id,idRtgs); 
                }        
            }
        }); 
        
    }
});


function getval2(sel){                
    var selected = sel.value;    

    if(selected !=""){
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/GetPerkiraanPrefund/" + selected,
            dataType: 'JSON',            
            success: function (data) {
                if (data.status == 'oke') {                    
                    $('#kode_perkiraan').val(data.kodePerkiraan);  	                
                    $('#id_perkiraan').val(data.idPerkiraan);  	                
                } else if (data.status == 'null') {
                    info_noti('Tidak Ada Data');	                
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


// function autoComplete(y){
//     var query = y.value;

//     $( "#kode_kliring" ).autocomplete({
//         source: function( request, response ) {
//             // Fetch data
//             $.ajax({
//                 url: "" + base_url + "/getDataKodeKliring",
//                 type: 'post',
//                 dataType: "json",
//                 data: {
//                     _token: CSRF_TOKEN,                    
//                     search: query
//                 },
//                 success: function( data ) {
//                     response( data );
//                 }
//             });
//         },
//         select: function (event, ui) {           
//             if(ui.item.label == "0"){
//                 error_noti('Kode Tidak Ditemukan'); 
                
//             } else {                
//                 var label = ui.item.label;                 
//                 var value = ui.item.value;    
//                 var data =  ui.item.data;

//                 var namaKliring = data.nama_kliring;
//                 $('#kode_kliring').val(label);                
//                 $('#id_kliring').val(value);                 
//                 $('#bank').val(namaKliring);                 
                
//                 return false;
//             }               
//         }
//     });
// }   

$("#search").keypress(function (e) {
    if(e.keyCode==13){
        var kode = $('#search').val();        
        $('#search').attr('readOnly', true);
        $('#method_field').val("SEARCH");    
        
        $.ajax({
            type: 'post',
            url: "" + base_url + "/search/prefund",
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
                    $('#id_jb').val(data.jbId);
                    $('#id_rtgs').val(data.jbIdRtgs);
                    $('#tgl').val(data.jbTgl);
                    $('#payment_detail').val(data.jbPaymentDet);
                    $('#from_member').val(data.jbFromMember);
                    $('#to_member').val(data.jbToMember);
                    $('#member_information').val(data.jbInfoMember);
                    $('#nominal').val(data.jbNominal);                    
                    $('#send_ref').val(data.jbSendRef);
                    $('#receiver_ref').val(data.jbReceiverRef);
                    $('#kode_kliring').val(data.jbKodeKliring);
                    $("#id_kliring").val(data.jbIdKliring).trigger('change');                                                                                                                
                    $("#rek_lawan_perk").val(data.jbRekLawan).trigger('change');                                
                    $('#kode_perkiraan').val(data.jbKodePerkiraan);                    
                
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

function deleteProses(id,idRtgs) {    
    
    $.ajax({
        type: 'post',
        url: "" + base_url + "/delete/prefund",
        dataType: 'JSON',        
        data: {
            _token: CSRF_TOKEN,
            id: id,
            idRtgs: idRtgs            
        },
        beforeSend: function(){
            BeforeSend();
        },
        complete: function(){
            AfterSend();
        },
        success: function (data) {
            if (data.status == 'delete_successful') {
                success_noti('Data Berhasil Terhapus');
                disableClearEntry();    
                clearSelect2();                        
            } else if (data.status == 'delete_failed') {
                error_noti('Data Gagal Dihapus');                
            } else {
                error_noti('Data Gagal Dihapus (Kesalahan Sistem)');                
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman' +message);
        }
    });
}

function formatRupiah(y){    
    
    $( "#nominal" ).on('keyup',(function (event) {
        $(this).val(function (index, value) {
            return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });                            
    }));
    
}

function insertUpdateInduk() {

    var form = $('#formEntry');
    
    
    if (form.valid() == true) {

        var method = $('#method_field').val();  

        if (method === "PUT") {
            info_noti('Data Sudah Pernah Disimpan');	

        } else{
            var action_url = "" + base_url + "/prefund";  
            var action_type = "Tambah";                      

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
                        $('#id_jb').val(data.idJb);                        
                        $('#id_rtgs').val(data.idRtgs);                        
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
        }            
        
    } else {                        
        error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
    }
}

var validator = $('#formEntry').validate({

    rules: {        
        no_bukti: {required: true},
        tgl: {required: true},
        from_member: {required: true},
        to_member: {required: true},
        nominal: {required: true, number:true},        
        kode_kliring: {required: true},
        id_kliring: {required: true},
        rek_lawan_perk: {required: true},
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