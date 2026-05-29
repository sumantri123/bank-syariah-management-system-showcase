var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {
	$('#bukaTransaksi').DataTable();
    dataBukaTransaksi();
    $('#tgl').attr('disabled', true);    
	
	$( "#btn_simpan_buka" ).click(function() {
		insertBukaTutup();
	});
	
	$('#tgl_buka_transaksi').change(function () {
		
		const result = Math.random().toString(36).substring(2,7);
		document.getElementById('token').value = result;
	});
});    


function dataBukaTransaksi(){
		
	$.ajax({
		type: 'post',
		url: "" + base_url + "/getDataJson/BukaTransaksi",
		dataType: 'JSON',
		data: {
			_token: CSRF_TOKEN
		},
		beforeSend: function(){
			var loading = '<div class="card"><div class="card-body"><div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div></div></div>';			
			$('#bukaTransaksi tbody').html(loading);
		},
		success: function (data) {
			
			if (data.status == 'oke') {
				var baris = '';
				var nomor = 1;
				$.each( data.bukaTransaksi, function( key, value ) {
				  //alert( key + ": " + value.id );
					baris += '<tr><td>'+nomor+'</td>';
					baris += '<td>'+value.buka_tanggal+'</td>';
					baris += '<td>';
					if(value.buka_aktif=='y'){
						baris += '<div class="form-check form-switch">';
					baris += '<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onclick="nonAktif('+value.id+')" checked>';
						baris += '	<label class="form-check-label" for="flexSwitchCheckChecked">Aktif</label>';
						baris += '</div>';
					}else{
						baris += '<div class="form-check form-switch">';
						baris += '<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onclick="Aktif('+value.id+')">';
						baris += '	<label class="form-check-label" for="flexSwitchCheckChecked">Non Aktif</label>';
						baris += '</div>';
					}
					baris += '</td>';
					baris += '<td align="center"><div class="font-22 text-primary" onclick="genToken('+value.id+')" >	<i class="lni lni-cog"></i></div></td>';
					baris += '<td>'+value.token+'</td>';
					baris += '<td>'+value.batas_token+'</td></tr>';
					nomor++;
				});
				baris += '';
				$('#bukaTransaksi tbody').html(baris);
			
			} else {
				error_noti('Data Tidak Tersedia');                     
			}
		},

		error: function (xmlhttprequest, textstatus, message) {
			error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
		}
	});
}

function handler(){
  alert("tes");
}

function genToken(id){
	
	$.ajax({
		type: 'post',
		url: "" + base_url + "/update/GenTokenBukaTransaksi",
		dataType: 'JSON',
		data: {
			_token: CSRF_TOKEN,
			'id': id
		},
		success: function (data) {
			
			if (data.status == 'insert_successful') {
				dataBukaTransaksi();
			
			}else if(data.status == 'insert_failed'){
				error_noti('update Failed');
			}else {
				error_noti('Error Koneksi');                     
			}
		},

		error: function (xmlhttprequest, textstatus, message) {
			error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
		}
	});
}

function nonAktif(id){
	
	$.ajax({
		type: 'post',
		url: "" + base_url + "/update/NonAktifBukaTransaksi",
		dataType: 'JSON',
		data: {
			_token: CSRF_TOKEN,
			'id': id
		},
		success: function (data) {
			
			if (data.status == 'insert_successful') {
				dataBukaTransaksi();
			
			}else if(data.status == 'insert_failed'){
				error_noti('update Failed');
			}else {
				error_noti('Error Koneksi');                     
			}
		},

		error: function (xmlhttprequest, textstatus, message) {
			error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
		}
	});
}


function Aktif(id){
	
	$.ajax({
		type: 'post',
		url: "" + base_url + "/update/AktifBukaTransaksi",
		dataType: 'JSON',
		data: {
			_token: CSRF_TOKEN,
			'id': id
		},
		success: function (data) {
			
			if (data.status == 'insert_successful') {
				dataBukaTransaksi();
			}else if(data.status == 'insert_failed'){
				error_noti('update Failed');
			}else {
				error_noti('Error Koneksi');                     
			}
		},

		error: function (xmlhttprequest, textstatus, message) {
			error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
		}
	});
}



function insertBukaTutup() {

    var form = $('#formEntryBukaTutup');
    if (form.valid() == true) {

		          
		var action_url = "" + base_url + "/bukaTransaksiSave";  
		var action_type = "Tambah";                      
	
		$.ajax({
			type: 'POST',
			url: action_url,
			dataType: 'JSON',
			data: form.serialize(),            

			success: function (data) {                    
				if (data.status == 'insert_successful') {                        
					success_noti('Berhasil ' + action_type + ' Data');
					dataBukaTransaksi();
																			
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
		
	} else {                        
		error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
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