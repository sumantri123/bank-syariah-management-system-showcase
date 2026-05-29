var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {    
    $('#contentJB').hide();
    disableEntry();

});    

$('button#btn_back').on('click', function () {                      
    
    $('#contentJB').hide("slow");
    $('#headerJB').show("slow");
    //$('#tableHeader').remove();
    
});

$('button#btn_new').on('click', function () {  
    
    enableEntry();      
    $("#id_rekening").select2().select2('val','""');
    $("#id_rekening").select2({ width: "100%" });        
    $('#method_field').val("POST");          
    
});

$('button#btn_print2').on('click', function () {                      
                
    var myModalHorizontalprint = document.getElementById('myModalHorizontalprint');
    var popupWin = window.open('', '_blank', 'left=0,top=0,width=1000,height=700,status=0');
    popupWin.document.open();
    popupWin.document.write('<html><body width="100%" onload="window.print()">' + myModalHorizontalprint.innerHTML + '</html>');
    popupWin.document.close();
    
});

$('button#btn_print').on('click', function () {   
    var form = $('#formEntry');
    if (form.valid() == true) {            
        $('#headerJB').hide("slow");
        $('#contentJB').show("slow");    
        loadData();
    } else {
        error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
    }
}); 

function getval(sel){                
    var selected = sel.value;
	$('#nama').val( $("#id_rekening2 option:selected").attr("nama"));  	                    
	$('#no_rekening').val($("#id_rekening2 option:selected").attr("norek"));     
}

function loadData(dateCari){   
    var noRemitt = document.getElementById("no_remitt").value;
    var noLc = document.getElementById("no_lc").value;    
    var nilaiNego = document.getElementById("nilai_nego").value;
    var komisiExpor = document.getElementById("komisi_expor").value;
    var biayaAdministrasi = document.getElementById("biaya_administrasi").value;
    var biayaAdministrasi2 = biayaAdministrasi.replace(",", "");
    var biayaDokumen = document.getElementById("biaya_dokumen").value;
    var biayaDokumen2 = biayaDokumen.replace(",", "");
    var nama = document.getElementById("nama").value;
    var noRekening = document.getElementById("no_rekening").value;
    var kursJual = document.getElementById("kurs_jual").value;
    var kursBeli = document.getElementById("kurs_beli").value;
    var kursTengah = (parseInt(kursJual) + parseInt(kursBeli))/2;
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    
    var nilaiDokumenNegosiasi = parseInt(nilaiNego) * parseInt(kursJual);
    var pendapatanSelisihKurs = ((parseInt(kursJual) - parseInt(kursBeli)) * parseInt(nilaiNego))/2;
    var nilaiDokumenDibukukan = (parseInt(nilaiDokumenNegosiasi) - parseInt(pendapatanSelisihKurs));
    var data4_3 = (parseInt(komisiExpor)/100) * parseInt(nilaiNego);
    var komisiNegosiasi = parseInt(data4_3) * parseInt(kursJual);
    var pendapatanJasaPengiriman = parseInt(biayaDokumen2) * parseInt(kursJual);
    var total = parseInt(nilaiDokumenDibukukan) + parseInt(pendapatanSelisihKurs) + parseInt(komisiNegosiasi) + parseInt(pendapatanJasaPengiriman) + parseInt(biayaAdministrasi2);
    
    today = yyyy + '-' + mm + '-' + dd;        

    $('#nama_print').text(nama);
    $('#no_remmit_print').text(noRemitt);
    $('#no_lc_print').text(noLc);
    $('#no_rekening_print').text(noRekening);
    $('#tgl_print').text(today);

    $('#data_I_1').text(convertToRupiahNoRp(nilaiNego));
    $('#data_I_2').text(convertToRupiahNoRp(kursJual));
    $('#data_I_3').text(convertToRupiahNoRp(nilaiDokumenNegosiasi));    
    $('#data_II_3').text(convertToRupiahNoRp(pendapatanSelisihKurs));        
    $('#data_III_3').text(convertToRupiahNoRp(nilaiDokumenDibukukan));        
    $('#data_IV_0').text(komisiExpor +" %");        
    $('#data_IV_1').text(convertToRupiahNoRp(data4_3));        
    $('#data_IV_2').text(convertToRupiahNoRp(kursJual));
    $('#data_IV_3').text(convertToRupiahNoRp(komisiNegosiasi));
    $('#data_V_1').text(convertToRupiahNoRp(biayaDokumen));        
    $('#data_V_2').text(convertToRupiahNoRp(kursJual));
    $('#data_V_3').text(convertToRupiahNoRp(pendapatanJasaPengiriman));    
    $('#data_VI_3').text(biayaAdministrasi);    
    $('#data_VII_3').text(convertToRupiahNoRp(total));    
    
}

function formatRupiah(y){
    var query = y.value;
    
    $( "#biaya_administrasi" ).on('keyup',(function (event) {
        $(this).val(function (index, value) {
            return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    }));   
    
    $( "#biaya_dokumen" ).on('keyup',(function (event) {
        $(this).val(function (index, value) {
            return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    }));   
}

var validator = $('#formEntry').validate({

    rules: {
        no_remitt: {required: true},
        id_rekening2: {required: true},
        no_lc: {required: true},
        nilai_nego: {required: true, number:true},
        komisi_expor: {required: true, number:true},
        setoran_jaminan: {required: true, number:true},
        biaya_administrasi: {required: true, number:true},
        biaya_dokumen: {required: true, number:true},
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