var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {    
    $('#searchGrup').hide("slow");
    addRow();        
    disableEntry();
    totalDebetKredit();    
});    

$('button#btn_search').on('click', function () {                      
    $('#searchGrup').show("slow");
    disableClearEntry();
    $('#myTable tr.body').remove();
    $('#search').attr('readOnly', false);
    $('#search').focus();
    totalDebetKredit();
    
});

$('button#btn_new').on('click', function () {  

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;

    enableEntry();  
    totalDebetKredit();
	$('#no_bukti').focus();
    $('#tgl').val(today);  
    $('#myTable tr.body').remove();
    $('#method_field').val("POST");  
    $('#search').attr('readOnly', true);       
    $('#searchGrup').hide("slow"); 
    $('#tgl').attr('readOnly', true);        
    $('#addSub').attr('disabled','disabled');	
});

$('button#btn_simpan').on('click', function () {   
    var method = $('#method_field').val();   
    if(method==="SEARCH") {
        info_noti('Data Tidak Boleh DiEdit/Disimpan');	                        
    } else {
		$("#addSub").removeAttr("disabled");
        insertUpdateInduk();
    }        
}); 

$('button#btn_delete').on('click', function () {                
    var id = document.getElementById("id_jb").value;
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
        var bagian = $('#bagian').val();
        $('#search').attr('readOnly', true);
        $('#method_field').val("SEARCH");    
        
        $.ajax({
            type: 'post',
            url: "" + base_url + "/search/jurnalBagianDet",
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
                    //sweetAlertDefault('<b>Data Berhasil Terhapus</b>', 'success', 2000 );                                        
                    $('#no_bukti').val(data.jbNo);
                    $('#id_jb').val(data.jbId);
                    $('#tgl').val(data.jbTgl);
                    $('#keterangan').val(data.jbKet);
					$('#addSub').attr('disabled','disabled');

                    var totDetData = data.data.length;
                    var b;
                    var content;
                    for (b = 0; b < totDetData; b++) {
                        // alert(data.data[b].jurnal_det_nominal);
                        var debet = ((data.data[b].id_jenis_transaksi)=="1") ? data.data[b].jurnal_det_nominal :"";
                        var kredit = ((data.data[b].id_jenis_transaksi)=="2") ? data.data[b].jurnal_det_nominal :"";

                        content += "<tr class='body' id='row_"+b+"'>"
                        content += "<td >"
                        content += "<input type='text' class='form-control form-control-sm' id='kode_perkiraan_"+b+"' value='"+data.data[b].kode_perkiraan+"' name='kode_perkiraan[]' readonly>"
                        content += "</td>"
                        content += "<td class='text-left'>"
                        content += "<input type='text' class='form-control form-control-sm' id='nama_perkiraan_"+b+"' value='"+data.data[b].nama_perkiraan+"' name='nama_perkiraan[]' readonly>"
                        content += "<input type='hidden' class='form-control form-control-sm' id='id_perkiraan_"+b+"' value='"+data.data[b].id_perkiraan+"' name='id_perkiraan[]' readonly>"
                        content += "<input type='hidden' class='form-control form-control-sm' id='jurbag_det_id_"+b+"' value='"+data.data[b].jurnal_det_id+"' name='jurbag_det_id[]' readonly>"
                        content += "</td>"
                        content += "<td ><input type='text' class='form-control form-control-sm' id='debet_"+b+"' value='"+convertToRupiahNoRp(debet)+"' name='debet[]' readonly></td>"
                        content += "<td ><input type='text' class='form-control form-control-sm' id='kredit_"+b+"' value='"+convertToRupiahNoRp(kredit)+"' name='kredit[]' readonly></td>"
                        content += "<td >"
                        content += "<div class='ms-auto d-flex align-items-center'>"
                        content += "<button type='button' class='btn btn-secondary  px-2 ms-2' ><i class='bx bxs-save me-0'></i></button>"
                        content += "<button type='button' class='btn btn-secondary  px-2 ms-2' ><i class='bx bxs-trash me-0'></i></button>"
                        content += "</div>"
                        content += "</td>"
                        content += "</tr>";
                    }
                     $("#myTable > tbody").append(content);
                     totalDebetKredit();
                
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

function totalDebetKredit(){
    var id = document.getElementById("id_jb").value;
    
    if(id!=""){
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/total/jurnalBagianDet/" + id,
            dataType: 'JSON',
            // beforeSend: function () {
            //     sweetAlertLoading('Memproses');
            // },
            success: function (data) {
                if (data.status == 'oke') {
                    //sweetAlertDefault('<b>Data Berhasil Terhapus</b>', 'success', 2000 );                                        
                    $('#tot_db').val(convertToRupiahNoRp(data.totDebet));
                    $('#tot_kr').val(convertToRupiahNoRp(data.totKredit));
                    var message = (data.totDebet == data.totKredit) ? "Balance":"Belum Balance";
                    $('#message').text(message);
                    
                } else {
                    //error_noti('Data Tidak Tersedia');     
                }
            },
    
            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
            }
        });
    } else {
        $('#tot_db').val("");
        $('#tot_kr').val("");
    }       
}

function formatRupiah(b, y){
    var query = y.value;
    
    $( "#debet_"+b ).on('keyup',(function (event) {
        $(this).val(function (index, value) {
            return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    }));

    $( "#kredit_"+b ).on('keyup',(function (event) {
        $(this).val(function (index, value) {
            return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    }));
}

function autoComplete(b, y){
    var query = y.value;
    //alert("myFunction"+b);
    //alert("my value"+query);

    $( "#kode_perkiraan_"+b ).autocomplete({
        source: function( request, response ) {
            // Fetch data
            $.ajax({
            url: "" + base_url + "/getIdPerJurnalBagian",
            type: 'post',
            dataType: "json",
            data: {
                _token: CSRF_TOKEN,
                //search: request.term
                search: query
            },
            success: function( data ) {
                response( data );
            }
            });
        },
        select: function (event, ui) {
            // Set selection
            //alert("after select"+b);
            if(ui.item.value == "0"){
                error_noti('Kode Tidak Ditemukan'); 
                
            } else {
                var value = ui.item.label;
                var splitValue = value.split("--");              
	
                $('#nama_perkiraan_'+b).val(splitValue[1]); // display the selected text
                $('#kode_perkiraan_'+b).val(splitValue[0]); // display the selected text
                $('#id_perkiraan_'+b).val(ui.item.value); // save selected id to input
                return false;
            }               
        }
    });
}    

function addRow(){
    var a = 0;
    var b = a++;
    
    var content = "<table id='myTable' border='1' class='classTable'>"
        content += "<thead>"
        content += "<tr>"
        content += "<th width='20%' class='text-center'><b>No. Perkiraan</b></th>"
        content += "<th width='30%' class='text-center'><b>Nama Perkiraan</b></th>"
        content += "<th width='20%' class='text-center'><b>Debet</b></th>"
        content += "<th width='20%' class='text-center'><b>Kredit</b></th>"
        content += "<th width='10%' class='text-center'><button type='button' class='btn btn-light px-2 ms-2' id='addSub' ><i class='bx bxs-message-square-add me-0'></i></button></th>"
        content += "</tr>"
        content += "</thead>"
        content += "<tbody>"
        content += "<tr id='row_"+b+"' class='body'>"
        content += "<td >"
        content += "<input type='text' class='form-control form-control-sm' id='kode_perkiraan_"+b+"' onkeyup='autoComplete("+b+", this)' value='' name='kode_perkiraan[]'>"            
        content += "<div id='kode_perkiraan_list_"+b+"'></div>"
        content += "</td>"
        content += "<td class='text-left'>"
        content += "<input type='text' class='form-control form-control-sm' id='nama_perkiraan_"+b+"' value='' name='nama_perkiraan[]' readonly>"
        content += "<input type='hidden' class='form-control form-control-sm' id='id_perkiraan_"+b+"' value='' name='id_perkiraan[]' readonly>"
        content += "<input type='hidden' class='form-control form-control-sm' id='jurbag_det_id_"+b+"' value='' name='jurbag_det_id[]' readonly>"
        content += "</td>"
        content += "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='debet_"+b+"' onkeyup='formatRupiah("+b+", this)' value='' name='debet[]'></td>"
        content += "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='kredit_"+b+"' onkeyup='formatRupiah("+b+", this)' value='' name='kredit[]'></td>"
        content += "<td >"
        content += "<div class='ms-auto d-flex align-items-center'>"
        content += "<button type='button' class='btn btn-success px-2 ms-2' id='saveId_"+b+"' onclick='saveDet("+b+")'><i class='bx bxs-save me-0'></i></button>"
        content += "<button type='button' class='btn btn-danger px-2 ms-2' onclick='delDet("+b+")'><i class='bx bxs-trash me-0'></i></button>"
        content += "</div>"
        content += "</td>"
        content += "</tr>"
        content += "</tbody>"
        content += "<tfoot>"
        content += "<tr>"        
        content += "<td colspan='2'><h4><b>TOTAL D/K</b></h4></td>"                
        content += "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='tot_db' value='' name='tot_db' readonly></td>"                
        content += "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='tot_kr' value='' name='tot_kr' readonly></td>"                
        content += "<td >"
        content += "<span id='message' class='badge bg-primary'></span>"
        content += "</td>"                
        content += "</tr>"        
        content += "</tfoot>";
    content += "</table>"
    $('#show_table').append(content);
            
    document.onkeydown = function(){
        if(window.event && window.event.keyCode == 113) {                                
            var b = a++;                

            $("#myTable > tbody").append(                    
                "<tr class='body' id='row_"+b+"'>"+
                    "<td >"+
                    "<input type='text' class='form-control form-control-sm' id='kode_perkiraan_"+b+"' onkeyup='autoComplete("+b+", this)' value='' name='kode_perkiraan[]'>"+                        
                    "</td>"+
                    "<td class='text-left'>"+
                    "<input type='text' class='form-control form-control-sm' id='nama_perkiraan_"+b+"' value='' name='nama_perkiraan[]' readonly>"+
                    "<input type='hidden' class='form-control form-control-sm' id='id_perkiraan_"+b+"' value='' name='id_perkiraan[]' readonly>"+
                    "<input type='hidden' class='form-control form-control-sm' id='jurbag_det_id_"+b+"' value='' name='jurbag_det_id[]' readonly>"+
                    "</td>"+
                    "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='debet_"+b+"' onkeyup='formatRupiah("+b+", this)' value='' name='debet[]'></td>"+
                    "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='kredit_"+b+"' onkeyup='formatRupiah("+b+", this)' value='' name='kredit[]'></td>"+
                    "<td >"+
                    "<div class='ms-auto d-flex align-items-center'>"+
                    "<button type='button' class='btn btn-success px-2 ms-2' id='saveId_"+b+"' onclick='saveDet("+b+")'><i class='bx bxs-save me-0'></i></button>"+
                    "<button type='button' class='btn btn-danger px-2 ms-2' onclick='delDet("+b+")'><i class='bx bxs-trash me-0'></i></button>"+
                    "</div>"+
                    "</td>"+
                "</tr>"
            );                
            document.getElementById("kode_perkiraan_"+b).focus();                
            
        }
    }
    
	$('#addSub').click(function(){
		
		var b = a++;                

		$("#myTable > tbody").append(                    
			"<tr class='body' id='row_"+b+"'>"+
				"<td >"+
				"<input type='text' class='form-control form-control-sm' id='kode_perkiraan_"+b+"' onkeyup='autoComplete("+b+", this)' value='' name='kode_perkiraan[]'>"+                        
				"</td>"+
				"<td class='text-left'>"+
				"<input type='text' class='form-control form-control-sm' id='nama_perkiraan_"+b+"' value='' name='nama_perkiraan[]' readonly>"+
				"<input type='hidden' class='form-control form-control-sm' id='id_perkiraan_"+b+"' value='' name='id_perkiraan[]' readonly>"+
				"<input type='hidden' class='form-control form-control-sm' id='jurbag_det_id_"+b+"' value='' name='jurbag_det_id[]' readonly>"+
				"</td>"+
				"<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='debet_"+b+"' onkeyup='formatRupiah("+b+", this)' value='' name='debet[]'></td>"+
				"<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='kredit_"+b+"' onkeyup='formatRupiah("+b+", this)' value='' name='kredit[]'></td>"+
				"<td >"+
				"<div class='ms-auto d-flex align-items-center'>"+
				"<button type='button' class='btn btn-success px-2 ms-2' id='saveId_"+b+"' onclick='saveDet("+b+")'><i class='bx bxs-save me-0'></i></button>"+
				"<button type='button' class='btn btn-danger px-2 ms-2' onclick='delDet("+b+")'><i class='bx bxs-trash me-0'></i></button>"+
				"</div>"+
				"</td>"+
			"</tr>"
		);                
		document.getElementById("kode_perkiraan_"+b).focus();    
			
	});
}    

function deleteProses(id) {    
    
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/delete/jurnalBagian/" + id,
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
                disableClearEntry();
                $('#myTable tr.body').remove();
                totalDebetKredit();
            } else if (data.status == 'delete_failed') {
                error_noti('Data Gagal Dihapus');
                totalDebetKredit();
            } else {
                error_noti('Data Gagal Dihapus (Kesalahan Sistem)');
                totalDebetKredit();
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });
}

function insertUpdateInduk() {

    var form = $('#formEntry');
    if (form.valid() == true) {

        var method = $('#method_field').val();                    
        var action_url = "" + base_url + "/jurnalBagian";  
        var action_type = "Tambah";                      
        if (method === "PUT") {
            action_url = "" + base_url + "/jurnalBagian/" + $('#id_jb').val();
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
                    $('#id_jb').val(data.id);                     
                    $('#method_field').val("PUT");
                    disableEntry();
                                                                            
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
        tgl: {required: true},
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

// Untuk Simpan Jurnal Detail

function saveDet(b){

    var idJurnalBagian = document.getElementById("id_jb").value;
    var idPerkiraan = document.getElementById("id_perkiraan_"+b).value;                
    var kodePerkiraan = document.getElementById("kode_perkiraan_"+b).value;                
    var debet = document.getElementById("debet_"+b).value;   
    var kredit = document.getElementById("kredit_"+b).value;   
    var jurnalDetNominal = (debet=="") ? kredit : debet;         
    var idJenisTransaksi = (debet=="") ? 2 : 1; // 1: debet, 2: kredit  
        
    if(idJurnalBagian==""){
        info_noti('Anda Belum Membuat No. Bukti');	                
        
    } else if((debet!="" && kredit!="")||(debet=="" && kredit=="")){
        info_noti('Entry Salah Satu (Debet / Kredit)');	                
        
    } else if(idPerkiraan==""){
        info_noti('Pastikan Anda Memilih Id Perkiraan / Nama Perkiraan Tidak Boleh Kosong');	

    }else {
        var action_url = "" + base_url + "/jurnalBagianDet";  
        var action_type = "Tambah";        
  

        $.ajax({
            type: 'POST',
            url: action_url,
            dataType: 'JSON',
            data: {_token: CSRF_TOKEN, idJB:idJurnalBagian, kodePer:kodePerkiraan, idPer:idPerkiraan, idTrans:idJenisTransaksi, nominal:jurnalDetNominal},            
            beforeSend: function(){
                BeforeSend();
            },
            complete: function(){
                AfterSend();
            },
            success: function (data) {                    
                if (data.status == 'insert_successful') {                        
                    success_noti('Berhasil ' + action_type + ' Data');
                    disableEntry();
					$('#saveId_'+b).attr('disabled','disabled');
                    $('#jurbag_det_id_'+b).val(data.id);
                    totalDebetKredit();
                                                                            
                } else if (data.status == 'insert_failed') {
                    
                    error_noti('Gagal ' + action_type + ' Data, '+data.msg); 
                    
                    //var errors = data.error;
                    //errorValidationLaravel(errors, '#error-validation');
                    totalDebetKredit();

                } else {
                    error_noti('Gagal ' + action_type + ' (Kesalahan Sistem)');
                    totalDebetKredit();
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, '+message);
            }

        });            
        //error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');

    }  
} 

function delDet(b){

    var idJurnalBagian = document.getElementById("id_jb").value;
    var idJurnalBagianDet = document.getElementById("jurbag_det_id_"+b).value;       
    var kode = document.getElementById("kode_perkiraan_"+b).value;
    
    if(idJurnalBagianDet==""){        
        $('#row_'+b).remove();
    } else {
        Lobibox.confirm({
            iconClass: true,
            title: 'Delete Data',                        
            msg: 'Yakin Hapus Transaksi: "' + kode + '" ?',            
            callback: function ($this, type, ev) {
                if(type=='yes'){
                    deleteProsesDet(idJurnalBagianDet, b, idJurnalBagian);   
                }        
            }
        });       
    }
} 

function deleteProsesDet(id, b, idJB) {
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/delete/jurnalBagianDet/" + id+"/" + idJB,
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
                $('#row_'+b).remove();
                totalDebetKredit();                
            } else if (data.status == 'delete_successfulx') {
                success_noti('Data Berhasil Terhapus');
                $('#row_'+b).remove();
                disableClearEntry();
                totalDebetKredit();
            } else if (data.status == 'delete_failed') {
                error_noti('Data Gagal Dihapus');
                totalDebetKredit();
            } else {
                error_noti('Data Gagal Dihapus (Kesalahan Sistem)');
                totalDebetKredit();
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });
}

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
