var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {        
    disableEntry();
    $('#searchGrup').hide("slow");

    //$("#ke").change(function(){
        // var idRekPin = document.getElementById("idRekPin").value;
        // var angsuranKe = $(this).val();
        // searchAngsuran(idRekPin, angsuranKe);
    //});    
});    

$('button#btn_search').on('click', function () {                      
    $('#searchGrup').show("slow");
	$('#btn_simpan').attr('disabled','disabled');	
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
    clearSelect2();
    $('#angsuran_ke').attr('readOnly', true);
    $('#pokok_pinjaman').attr('readOnly', true);
    $('#bunga_efektif').attr('readOnly', true);
    $('#kode').attr('readOnly', true);
    $('#amort').attr('readOnly', true);
    $('#pembayaran_angsuran').attr('readOnly', true);
    $('#keterangan').attr('readOnly', true);
    $('#no_bukti').focus();    
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
    var idPin = document.getElementById("pinjaman_angsuran_id").value;   

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
                    deleteProses(id,idPin); 
                }        
            }
        }); 
        
    }
});

// function autoComplete(y){
//     var query = y.value;

//     $( "#search" ).autocomplete({
//         source: function( request, response ) {
//             // Fetch data
//             $.ajax({
//                 url: "" + base_url + "/getDataRekAngsuran",
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
//                 $('#search').val(label);                
//                 $('#idRekPin').val(value); 
//                 $("select#ke").html(data.data_users);                
                
//                 return false;
//             }               
//         }
//     });
// }   


function getval(sel){                
    var selected = sel.value;

    if(selected !=""){
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/GetDataAngsuran/" + selected,
            dataType: 'JSON',            
            success: function (data) {
                if (data.status == 'oke') {                                             
                    //var kode = (data.sukuBungaEfektif < 0) ? "K":"D";
                    $('#angsuran_ke').val(data.ke);
                    $('#pokok_pinjaman').val(convertToRupiahNoRp(Math.round(data.angsuranPokok)));
                    $('#bunga_efektif').val(convertToRupiahNoRp(Math.round(data.maginBagiHasil)));
                    //$('#amort').val(convertToRupiahNoRp(Math.round(data.amortisasi)));
                    $('#pembayaran_angsuran').val(convertToRupiahNoRp(Math.round(data.totAngsur)));                    
                    //$('#kode').val(kode);
                    $('#keterangan').val("Angsuran "+data.noRek);
                    $('#pinjaman_angsuran_id').val(data.idAngsuran);
                    $('#id_per_pinjaman').val(data.idPerkiraanPinjaman);
                    $('#id_per_bunga').val(data.idPerkiraanProvisi);
                    $('#id_rekening').val(data.idRekening);
					$('#jenis_pinjaman').val(data.jenisPinjaman);
					
					if((data.jenisPinjaman)==2){
						$('#pokok_pinjaman').attr('readOnly', false);
						$('#bunga_efektif').attr('readOnly', false);
						$('#pembayaran_angsuran').attr('readOnly', false);
					} else {
						$('#pokok_pinjaman').attr('readOnly', true);
						$('#bunga_efektif').attr('readOnly', true);
						$('#pembayaran_angsuran').attr('readOnly', true);
					}
					
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

function getval2(sel){                
    var selected = sel.value;    

    if(selected !=""){
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/GetPerkiraanAngsuran/" + selected,
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

// function searchAngsuran(idRekPin,angsuranKe){      
           
//     var method = $('#method_field').val();
// 	var action_url = "" + base_url + "/getDataJson/angsuranKe";
// 	var action_type = "Tambah";

// 	$.ajax({

// 		type: 'POST',
// 		url: action_url,
// 		dataType: 'JSON',        
//         data: {
//             _token: CSRF_TOKEN,            
//             idRekPin: idRekPin,
//             ke:angsuranKe
//         },				
// 		success: function (data) {

// 			if (data.status == 'successful') {
// 				$("select#user_kelas").html(data.data_users);
// 			} else if (data.status == 'failed') {
// 				error_noti('Gagal ' + action_type + ' Data'); 
// 			} else {
// 				error_noti('Gagal ' + action_type + ' (Kesalahan Sistem)');
// 			}
// 		},

// 		error: function (xmlhttprequest, textstatus, message) {
// 			error_noti('Koneksi Ke Server Gagal, '+message);
// 		}

// 	});
// }

$("#search").keypress(function (e) {
    if(e.keyCode==13){
        var kode = $('#search').val();
        var bagian = $('#bagian').val();
        $('#search').attr('readOnly', true);
        $('#method_field').val("SEARCH");    
        
        $.ajax({
            type: 'post',
            url: "" + base_url + "/search/angsuran",
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
					$('#kode').val(data.jbKode);
                    $('#bagian').val(data.jbBag);
                    $('#angsuran_ke').val(data.jbAngsuranKe);                    
                    $('#pokok_pinjaman').val(data.jbAngPokok);
                    $('#bunga_efektif').val(data.jbBungaEfektif);
                    $('#amort').val(data.jbAmortisasi);
                    $('#pembayaran_angsuran').val(data.jbPembayaranAng);
					$("#id_rekening2").val(data.jbIdRekeningPinjaman).trigger('change');                                                            
                    $("#rek_lawan_perk").val(data.jbIdRekeningLawan).trigger('change'); 
					
					$('#btn_simpan').attr('disabled','disabled');
					
                   /*  var totDetData = data.data.length;
                    var b;
                    var content;    */                 
                
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


function deleteProses(id,idPin) {    
    
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/delete/angsuran/" + id +"/"+idPin,
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
                clearSelect2();                        
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
    
    
    if (form.valid() == true) {

        var method = $('#method_field').val();  

        if (method === "PUT") {
            info_noti('Data Sudah Pernah Disimpan');	

        } else{
            var action_url = "" + base_url + "/angsuran";  
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
                        $('#id_jb').val(data.id);
                        $('#pinjaman_angsuran_id').val(data.idAngsur);                     
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
        id_rekening2: {required: true},
        rek_lawan_perk: {required: true},
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