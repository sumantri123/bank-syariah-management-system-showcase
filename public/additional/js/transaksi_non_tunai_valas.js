var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {    
    $('#searchGrup').hide("slow");
    addRow();        
    disableEntry();
//    $('#tgl').attr('disabled', true);    
});    

$('button#btn_search').on('click', function () {                          
    disableClearEntry();
    clearSelect2();        
    $('#myTable tr.body').remove();    
    $('#searchGrup').show("slow");
    $('#search').attr('readOnly', false);
    $('#search').focus();    
    //$('#tgl').attr('disabled', true);        
    //totalDebetKredit();
    
});

$('button#btn_new').on('click', function () {  
    
    enableEntry();  
    clearSelect2();        
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;    
    $('#no_bukti').focus();
    $('#tgl').attr('readOnly', true);        
    $('#tgl').val(today);          
    $("#id_rekening").select2({ width: "100%" });    
    $('#id_transaksi').val("");
    $('#method_field').val("POST");      
    $('#searchGrup').hide("slow"); 
    $('#search').attr('readOnly', true);    
    // totalDebetKredit();    

    //$('#myTable tr.body').remove();    
    
   
    
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
    var id = document.getElementById("idTrGr").value;
    var no_bukti = document.getElementById("no_bukti").value;
    var pass = $('#pass').val();
    if(id==""){
        info_noti('Silahkan Simpan Data Anda Terlebih Dahulu');	                 
        //sweetAlertDefault('<b>Silahkan Simpan Data Anda Terlebih Dahulu </b>', 'error', 2000 );
    } else {  
        
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
                            msg: 'Yakin Hapus Kode Bukti : "' + no_bukti + '" ?',            
                            callback: function ($this, type, ev) {
                                if(type=='yes'){
                                    deleteProses(id); 
                                }        
                            }
                        });  
                    } else {                                    
                        error_noti('Password Salah, Transaksi Batal');                                    
                    }        
                }        
            }
        });               
    }
});

function addRow(){
    
    var content = "<label for='inputCity' class='form-label' style='color:blue; font-weight:bold'>Transaksi</label>"
        content += "<table id='myTable' border='1' class='classTable table-sm'>"
        content += "<thead>"
        content += "<tr>"
        content += "<th width='30%' style='font-size:12px' class='text-center'><b>No.Bukti</b></th>"
        content += "<th width='35%' style='font-size:12px' class='text-center'><b>Tgl</b></th>"
        content += "<th width='15%' style='font-size:12px' class='text-center'><b>Rek Nomor</b></th>"
        content += "<th width='10%' style='font-size:12px' class='text-center'><b>Debit</b></th>"
        content += "<th width='10%' style='font-size:12px' class='text-center'><b>Kredit</b></th>"
        content += "</tr>"
        content += "</thead>"
        content += "<tbody>"
        content += "<tr class='body'>"
        content += "<td class='unit'></td>"                        
        content += "<td class='text-left'></td>"
        content += "<td class='unit'></td>"
        content += "<td class='qty'></td>"
        content += "<td class='unit'>"
        content += "<div class='ms-auto d-flex align-items-center'>"        
        content += "</div>"
        content += "</td>"
        content += "</tr>"
        content += "</tbody>"        
    content += "</table>"
    $('#show_table').append(content);                
    
}

$("#search").keypress(function (e) {
    if(e.keyCode==13){
        var kode = $('#search').val();
        var bagian = $('#bagian').val();
        $('#search').attr('readOnly', true);
        $('#method_field').val("SEARCH");    
        
        $.ajax({
            type: 'post',
            url: "" + base_url + "/search/tranNonTuVa",
            dataType: 'JSON',
            data: {
                _token: CSRF_TOKEN,
                bagian: bagian,
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
                    $('#idTrGr').val(data.jbId);
                    $('#tgl').val(data.jbTgl);
                    $('#id_perkiraan1').val(data.jbIdPerkiraan1);
                    $('#keterangan').val(data.jbKet);                    
                    $('#nominal').val(convertToRupiahNoRp(data.jbNominal));
                    $("#id_rekening").val(data.jbIdRekening).trigger('change');                                        
                    $("#id_rekening").select2({ width: "100%" });                    
                    $("#id_rekening_lawan").val(data.jbIdRekening2).trigger('change');
                    $("#transaksi").val(data.jbTransaksi).trigger('change');
                    $('#id_transaksi').val(data.jbIdTransaksi);                       
                    $('#id_perkiraan2').val(data.jbRekLawan);  	                
                    $('#no_rekening2').val(data.jbNoRekening2);
                    $('#no_rekening1').val(data.jbNoRekening);

                    var content;
                    var debet = ((data.jbIdTransaksi)=="1") ? data.jbNominal :"0";
                    var kredit = ((data.jbIdTransaksi)=="2") ? data.jbNominal :"0";
                    content += "<tr class='body'>"
                    content += "<td class='unit' style='font-size:12px'>"+data.jbNo+"</td>"                                        
                    content += "<td class='text-left' style='font-size:12px'>"+data.jbTgl+"</td>"                    
                    content += "<td class='unit' style='font-size:12px'>"+data.jbNoRekening+"</td>"
                    content += "<td class='qty' style='font-size:12px'>"+convertToRupiahNoRp(debet)+"</td>"
                    content += "<td class='unit' style='font-size:12px'>"+convertToRupiahNoRp(kredit)+"</td>"                                        
                    content += "</tr>";
                    
                     $("#myTable > tbody").append(content);

                   
                
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

function getval3(sel){                
    var selected = sel.value;    
	  var text_select = $( "#transaksi option:selected" ).text();
//      alert(text_select);
$('#keterangan_transaksi').val(text_select);
/*
    if(selected === "2.1"){
        $('#keterangan_transaksi').val("Penjualan Bank Notes");  	                
    } else if (selected === "2.2"){
        $('#keterangan_transaksi').val("Pembelian Bank Notes");  	                
    } else if (selected === "3.1"){
        $('#keterangan_transaksi').val("Penjualan TC");  	                
    } else if (selected === "3.2"){
        $('#keterangan_transaksi').val("Pembelian TC");  	                
    } else {
        $('#keterangan_transaksi').val("");  	                
    }*/
}

function getval(sel){                
    var selected = sel.value;
	$('#id_perkiraan1').val( $("#id_rekening option:selected").attr("perkiraan"));  	                
    $('#id_tab').val($("#id_rekening option:selected").attr("norek"));  
	$('#no_rekening').val($("#id_rekening option:selected").attr("no_rekening"));  

   /* if(selected !=""){
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/GetPerkiraan1/" + selected,
            dataType: 'JSON',            
            success: function (data) {
                if (data.status == 'oke') {                    
                    $('#id_perkiraan1').val(data.idPerkiraan);  	                
                    $('#no_rekening').val(data.noRekening);  	                
                } else if (data.status == 'null') {
                    info_noti('Tidak Ada Data Rekening');	                
                } else {
                    error_noti('Gagal (Kesalahan Sistem)');                     
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');                 
            }
        });
   }*/
}

function getval2(sel){                
    var selected = sel.value;    
    var rekAsal = document.getElementById("no_rekening").value;
    var transaksi = document.getElementById("keterangan_transaksi").value;
	$('#id_perkiraan2').val( $("#id_rekening_lawan option:selected").attr("perkiraan"));  	                
    $('#id_tab2').val($("#id_rekening_lawan option:selected").attr("norek"));  
	
	$('#keterangan').val("Transaksi "+rekAsal+" Ke "+$("#id_rekening_lawan option:selected").attr("no_rekening") +" ("+transaksi+")");
    
   /* if(selected !=""){
        $.ajax({
            type: 'post',
            url: "" + base_url + "/GetPerkiraan1",
            dataType: 'JSON', 
            data: {
                _token: CSRF_TOKEN,                    
                id: selected,
                rekAsal: rekAsal,
            },           
            success: function (data) {
                if (data.status == 'oke') {                    
                    $('#id_perkiraan2').val(data.idPerkiraan);  	                
                    $('#no_rekening2').val(data.noRekening);
                    $('#keterangan').val(data.keterangan);
                } else if (data.status == 'null') {
                    info_noti('Tidak Ada Data Rekening');	                
                } else {
                    error_noti('Gagal (Kesalahan Sistem)');                     
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');                 
            }
        });
   }*/
}

// function totalDebetKredit(){
//     var id = document.getElementById("id_jb").value;
    
//     if(id!=""){
//         $.ajax({
//             type: 'GET',
//             url: "" + base_url + "/total/jurnalBagianDet/" + id,
//             dataType: 'JSON',
//             // beforeSend: function () {
//             //     sweetAlertLoading('Memproses');
//             // },
//             success: function (data) {
//                 if (data.status == 'oke') {
//                     //sweetAlertDefault('<b>Data Berhasil Terhapus</b>', 'success', 2000 );                                        
//                     $('#tot_db').val(convertToRupiahNoRp(data.totDebet));
//                     $('#tot_kr').val(convertToRupiahNoRp(data.totKredit));
//                     var message = (data.totDebet == data.totKredit) ? "Balance":"Belum Balance";
//                     $('#message').text(message);
                    
//                 } else {
//                     //error_noti('Data Tidak Tersedia');     
//                 }
//             },
    
//             error: function (xmlhttprequest, textstatus, message) {
//                 error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
//             }
//         });
//     } else {
//         $('#tot_db').val("");
//         $('#tot_kr').val("");
//     }       
// }

function formatRupiah(y){
    var query = y.value;
    
    $( "#nominal" ).on('keyup',(function (event) {
        $(this).val(function (index, value) {
            return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    }));    
}

function deleteProses(id) {        

    $.ajax({
        type: 'GET',
        url: "" + base_url + "/delete/tranNonTuVa/" + id,
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
                $('#myTable tr.body').remove();    
                $('#idTrGr').val("");
                $("#id_rekening").select2().select2('val','""');
                $("#rek_lawan_perk").select2().select2('val','""');                    
                $("#id_rekening").select2({ width: "100%" });    
                $('#id_transaksi').val(""); 
                disableClearEntry();
                //$('#myTable tr.body').remove();
                //totalDebetKredit();
            } else if (data.status == 'delete_failed') {
                error_noti('Data Gagal Dihapus');
                //totalDebetKredit();
            } else {
                error_noti('Data Gagal Dihapus (Kesalahan Sistem)');
                //totalDebetKredit();
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });
}

function insertUpdateInduk() {

    var form = $('#formEntry');
    $('#myTable tr.body').remove();    
    if (form.valid() == true) {

        var method = $('#method_field').val();                    
        var action_url = "" + base_url + "/tranNonTuVa";  
        var action_type = "Tambah";                      
        if (method === "PUT") {
            action_url = "" + base_url + "/tranNonTuVa/" + $('#idTrGr').val();
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
                    $('#idTrGr').val(data.id);                     
                    $('#method_field').val("PUT");
                    disableEntry();

                    var content;
                    var debet = ((data.jbIdTransaksi)=="1") ? data.jbNominal :"0";
                    var kredit = ((data.jbIdTransaksi)=="2") ? data.jbNominal :"0";
                    content += "<tr class='body'>"
                    content += "<td class='unit' style='font-size:12px'>"+data.jbNo+"</td>"                                        
                    content += "<td class='text-left' style='font-size:12px'>"+data.jbTgl+"</td>"                    
                    content += "<td class='unit' style='font-size:12px'>"+data.jbNoRekening+"</td>"
                    content += "<td class='qty' style='font-size:12px'>"+convertToRupiahNoRp(debet)+"</td>"
                    content += "<td class='unit' style='font-size:12px'>"+convertToRupiahNoRp(kredit)+"</td>"                                        
                    content += "</tr>";
                    
                    $("#myTable > tbody").append(content);
                                                                            
                } else if (data.status == 'insert_failed') {
                    
                    error_noti('Gagal ' + action_type + ' Data'+ data.msg); 
                    
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

var validator = $('#formEntry').validate({

    rules: { 
        no_bukti: {required: true},       
    //    id_rekening: {required: true},
        id_transaksi: {required: true},
        nominal: {required: true},
        tgl: {required: true},        
        rek_lawan_perk: {required: true},
        keterangan: {required: true},
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
