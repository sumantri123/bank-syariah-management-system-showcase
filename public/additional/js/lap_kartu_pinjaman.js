var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {           
    $('#contentJB').hide();
    disableEntry();
});    

$('button#btn_print').on('click', function () {                      
                
    var popupWin = window.open('', '_blank', 'left=0,top=0,width=1000,height=700,status=0');
    popupWin.document.open();
    popupWin.document.write('<html><head><title>Print it!</title>');
    popupWin.document.write('<link href="bank_stiep/css/app.css" rel="stylesheet">');
    popupWin.document.write('<link href="bank_stiep/css/bootstrap.min.css" rel="stylesheet">');
    popupWin.document.write('</head><body width="100%" onload="window.print()" style="margin:30px">');
    popupWin.document.write($("#myModalHorizontalprint").html());    
    popupWin.document.write('</body></html>');    
    popupWin.document.close();
    
});

$('button#btn_search').on('click', function () {                

    var id = document.getElementById("id_rekening2").value;       
    var form = $('#formEntry');

    if (form.valid() == true) {  
        $('#headerJB').hide("slow");
        $('#contentJB').show("slow");    
        loadData(id);
    } else {
        error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
    }
});

$('button#btn_back').on('click', function () {                      
    
    $('#contentJB').hide("slow");
    $('#headerJB').show("slow");
    $('#myTable').remove();
    
});

function loadData(id){   
        
    $.ajax({
        type: 'POST',
        url: "" + base_url + "/getDataKP/lapKartuPinjaman",        
        dataType: 'JSON',
        data: {
            _token: CSRF_TOKEN,
            //search: request.term
            id: id
        },       
        beforeSend: function (){
            $("#loading").show(1000);
        },
        success: function (data) {
            $("#loading").hide();
            if (data.status == 'oke') { 
                
                if(data.total >0){
                    var totDetData = data.data.length;     
                    var totEstimasi = 0;                    
                    var totAngsuranPokok = 0;
                    var totTagihanBunga = 0;                    

                    var b;                                                                                     
                    
                    var content = "<div style='overflow-x:auto;'>"
                        content += "<table id='myTable' border='1' class='table table-striped table-bordered'>"
                        content += "<thead>"
                        content += "<tr class='table-primary'>"                                               
                        content += "<th width='5%' class='text-center'><b>Angs. Ke</b></th>"
                        content += "<th width='10%' class='text-center'><b>Tgl Jth Tempo</b></th>"                                                
                        content += "<th width='10%' class='text-center'><b>Estimasi<br>Arus Kas</b></th>"
                        content += "<th width='10%' class='text-center'><b>Saldo Awal<br>Arus Kas</b></th>"                                                
                        content += "<th width='10%' class='text-center'><b>Angsuran Pokok</b></th>"                        
                        content += "<th width='10%' class='text-center'><b>Tagihan Margin</b></th>"                                                
                        content += "<th width='10%' class='text-center'><b>Saldo Akhir<br>Arus Kas</b></th>"                        
                        content += "<th width='5%' class='text-center'><b>Status</b></th>"         
                        content += "<th width='10%' class='text-center'><b>Tgl Bayar</b></th>"                                                               
                        content += "</tr>"
                        content += "</thead>"
                        content += "<tbody>"                    
                    
                    for (b = 0; b < totDetData; b++) {
                        
                        totEstimasi += parseInt(Math.round(data.data[b].estimasi)); 
                        totAngsuranPokok += parseInt(Math.round(data.data[b].angsuran_pokok)); 
                        totTagihanBunga += parseInt(Math.round(data.data[b].tagihan_bunga)); 
                        var tglBayar = (data.data[b].tanggal_bayar!=null) ? data.data[b].tanggal_bayar:"";
                        var status = (data.data[b].status!='n') ? "<span class='badge bg-primary'>L</span>":"";

                        content += "<tr class='body'>"                        
                        content += "<td class='text-center'>"+data.data[b].angsuran_ke+"</td>"
                        content += "<td class='text-left'>"+data.data[b].tanggal_jth_tempo+"</td>"                         
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(Math.round(data.data[b].estimasi))+"</span></td>"
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(Math.round(data.data[b].saldo_awal))+"</span></td>"                        
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(Math.round(data.data[b].angsuran_pokok))+"</span></td>"
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(Math.round(data.data[b].tagihan_bunga))+"</span></td>"                        
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(Math.round(data.data[b].saldo_akhir))+"</span></td>"                        
                        content += "<td class='text-center'>"+status+"</td>"                         
                        content += "<td class='text-left'>"+tglBayar+"</td>"                         
                        content += "</tr>";                          
                               
                        
                    }

                    content += "</tbody>"
                    content += "<tfoot>"
                    content += "<tr class='table-primary'>"        
                    content += "<td colspan=2><b>Jumlah</b></td>"                                    
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totEstimasi)+"</b></span></td>"                
                    content += "<td><span style='float: right;'><b></b></span></td>"                                    
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totAngsuranPokok)+"</b></span></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totTagihanBunga)+"</b></span></td>"                                    
                    content += "<td></td>"
                    content += "<td></td>"
                    content += "<td></td>"
                    content += "</tr>"          
                    content += "</tfoot>"
                    content += "</table>"
                    content += "</div>";

                    $('#show_table').append(content);
                    $('#jenisPinjaman').text(data.jenisPinjaman); 
					$('#namaNasabah').text(data.namaNasabah); 
					$('#noRekening').text(data.noRekening); 
                    $('#nominalPinjaman').text(convertToRupiahNoRp(data.nominalPinjaman)); 
                    $('#jangkaWaktu').text(data.jangkaWaktu); 
                    $('#noRek').text(data.noRek); 
                    $('#bungaNominalPersen').text(data.bungaPersen+" %");                                         
                    $('#bungaNominal').text(convertToRupiahNoRp(data.bungaNominal)); 

                } else {
                    var content = "<div id='myTable' class='alert alert-danger border-0 bg-danger alert-dismissible fade show py-2'>"
                        content += "<div class='d-flex align-items-center'>"
                        content += "<div class='font-35 text-white'><i class='bx bxs-message-square-x'></i></div>"                            
                        content += "<div class='ms-3'>"
                        content += "<h6 class='mb-0 text-white'>Note :</h6>"
                        content += "<div class='text-white'>Tidak Ada Data Pada</div>"
                        content += "</div></div>"                        
                        content += "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>"
                        content += "</div>"
                    $('#show_table').append(content);
                }                
                     
            } else {
                error_noti('Data Tidak Tersedia');       
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });
}

var validator = $('#formEntry').validate({

    rules: {                
        id_rekening2: {required: true},                                                                                
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