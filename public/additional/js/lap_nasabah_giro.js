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
        url: "" + base_url + "/getDataLapNasgiru/lapNGR",        
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
                    var bulanIni = [];
                    var total;                                
                    
                    var content = "<div style='overflow-x:auto;'>"
                        content += "<table id='myTable' border='1' class='table table-striped table-bordered'>"                    
                        content += "<thead>"
                        content += "<tr class='table-primary'>"                       
                        // content += "<th width='10%' class='text-center'><b>Detail</b></th>" 
                        content += "<th width='5%' class='text-center'><b>No</b></th>"
                        content += "<th width='25%' class='text-center'><b>Nomor Rekening</b></th>"                                                
                        content += "<th width='40%' class='text-center'><b>Nama Nasabah</b></th>"
                        content += "<th width='20%' class='text-center'><b>Saldo</b></th>"                        
                        content += "</tr>"
                        content += "</thead>"
                        content += "<tbody>"                    

                    total = 0;
                    for (b = 0; b < totDetData; b++) {                         

                        if(data.data[b].df_trans_perkiraan === "2"){
                            bulanIni[b] = data.data[b].kredit - data.data[b].debit;
                        } else {
                            bulanIni[b] = data.data[b].debit - data.data[b].kredit;
                        }
                        
                        var prk = ((data.data[b].prk) ==='y') ? "<i class='bx bxs-star text-warning'></i>":"";
                        content += "<tr class='body' id='row_"+b+"'>"
                        // content += "<td class='text-center'><button type='button' class='btn btn-primary btn-sm'><i class='bx bxs-search me-0'></i></button></td>"
                        content += "<td class='text-center'>"+(b+1)+"</td>"
                        content += "<td class='text-left'>"
                        content += data.data[b].nomor_rekening+" "+ prk
                        content += "</td>"                         
                        content += "<td class='text-left'>"+(data.data[b].nama)+"</td>"                         
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(bulanIni[b])+"</span></td>"                                            
                        content += "</tr>";  
                        total += parseInt(bulanIni[b]);                                          
                                                                                                             
                    }

                    content += "</tbody>"
                    content += "<tfoot>"
                    content += "<tr class='body table-primary' id='row_"+b+"'>"
                    content += "<td colspan='3' class='text-center'><b>Jumlah</b></td>"                         
                    content += "<td><b><span style='float: right;'>"+convertToRupiahNoRp(total)+"</span></b></td>"                    
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