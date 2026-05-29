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
    
    // var myModalHorizontalprint = document.getElementById('myModalHorizontalprint');
    // var popupWin = window.open('', '_blank', 'left=0,top=0,width=1000,height=700,status=0');
    // popupWin.document.open();
    // popupWin.document.write('<html><body width="100%" onload="window.print()">' + myModalHorizontalprint.innerHTML + '</html>');
    // popupWin.document.close();
    
});

$('button#btn_search').on('click', function () {                

    var dateCari = document.getElementById("tgl_cari").value;       

    var form = $('#formEntry');

    if (form.valid() == true) {    
        $('#headerJB').hide("slow");
        $('#contentJB').show("slow");    
        loadData(dateCari);
    } else {
        error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
    }
    
});

$('button#btn_back').on('click', function () {                      
    
    $('#contentJB').hide("slow");
    $('#headerJB').show("slow");
    $('#myTable').remove();
    
});

function loadData(dateCari){   
    
    $.ajax({
        type: 'POST',
        url: "" + base_url + "/getDataLapKliringSerah/lapKliringSerah",        
        dataType: 'JSON',
        data: {
            _token: CSRF_TOKEN,
            //search: request.term
            date: dateCari
        },       
        beforeSend: function (){
            $("#loading").show(1000);
        },
        success: function (data) {
            $("#loading").hide();
            if (data.status == 'oke') { 
                
                if(data.total >0){
                    var totDetData = data.data.length;                    
                    var b;
                    var totKredit= 0;
                    var totDebet = 0;
                    
                    var content = "<div style='overflow-x:auto;'>"
                        content += "<table id='myTable' border='1' class='table table-striped table-bordered'>"
                        content += "<thead>"
                        content += "<tr class='table-primary'>"                       
                        // content += "<th width='10%' class='text-center'><b>Detail</b></th>" 
                        content += "<th width='5%' class='text-center'><b>No</b></th>"
                        content += "<th width='15%' class='text-center'><b>Kode</b></th>"                                                
                        content += "<th width='20%' class='text-center'><b>Keterangan</b></th>"
                        content += "<th width='20%' class='text-center'><b>Debet</b></th>"                        
                        content += "<th width='20%' class='text-center'><b>Kredit</b></th>"                                                
                        content += "</tr>"
                        content += "</thead>"
                        content += "<tbody>"                    
                    
                    for (b = 0; b < totDetData; b++) {                                                 
                        
                        var debet = (data.data[b].debit === null) ? 0 : data.data[b].debit;
                        var kredit = (data.data[b].kredit === null) ? 0 : data.data[b].kredit;

                        totDebet += parseInt(debet);                        
                        totKredit += parseInt(kredit);  
                        content += "<tr class='body' id='row_"+b+"'>"
                        // content += "<td class='text-center'><button type='button' class='btn btn-primary btn-sm'><i class='bx bxs-search me-0'></i></button></td>"
                        content += "<td class='text-center'>"+(b+1)+"</td>"
                        content += "<td class='text-left'>"+data.data[b].kode_transaksi_kliring+"</td>"                         
                        content += "<td class='text-left'>"+data.data[b].nama_transaksi_kliring+"</td>"                         
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(debet)+"</span></td>"                        
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(kredit)+"</span></td>"                        
                        content += "</tr>";                          
                                                                                                             
                    }

                    content += "</tbody>"
                    content += "<tfoot>"
                    content += "<tr class='table-primary'>"        
                    content += "<td colspan='3'><b>Jumlah</b></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totDebet)+"</b></span></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totKredit)+"</b></span></td>"                                    
                    content += "</tr>"          
                    content += "</tfoot>"
                    content += "</table>"
                    content += "</div>";
                    $('#show_table').append(content);

                } else {
                    var content = "<div id='myTable' class='alert alert-danger border-0 bg-danger alert-dismissible fade show py-2'>"
                        content += "<div class='d-flex align-items-center'>"
                        content += "<div class='font-35 text-white'><i class='bx bxs-message-square-x'></i></div>"                            
                        content += "<div class='ms-3'>"
                        content += "<h6 class='mb-0 text-white'>Note :</h6>"
                        content += "<div class='text-white'>Tidak Ada Data Pada Tanggal Yang Anda Pilih</div>"
                        content += "</div></div>"                        
                        content += "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>"
                        content += "</div>"
                    $('#show_table').append(content);
                }
                $("#per_tanggal").text("Sampai Tanggal : "+data.date ?? "");    
                     
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
        tgl_cari: {required: true},                                                                                
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