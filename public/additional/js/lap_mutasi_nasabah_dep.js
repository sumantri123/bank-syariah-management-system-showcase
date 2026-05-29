var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {           
    $('#contentJB').hide();
    //loadData();
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
        url: "" + base_url + "/getData/lapMutdep",        
        dataType: 'JSON',
        data: {
            _token: CSRF_TOKEN,            
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
                    var totDebet = 0;
                    var totKredit = 0;                    
                    var saldoAkhir;
                    var totSaldoAwal = 0                    
                    var totSaldoAkhir = 0                                        
                    
                    var content = "<div style='overflow-x:auto;'>"
                        content += "<table id='myTable' border='1' class='table table-striped table-bordered'>"
                        content += "<thead>"
                        content += "<tr class='table-primary'>"
                        content += "<th width='3%' class='text-center'><b>No.</b></th>"
                        content += "<th width='15%' class='text-center'><b>No. Rekening</b></th>"
                        content += "<th width='20%' class='text-center'><b>Nama Nasabah</b></th>"
                        content += "<th width='10%' class='text-center'><b>Saldo Awal</b></th>"                        
                        content += "<th width='10%' class='text-center'><b>Debet</b></th>"
                        content += "<th width='10%' class='text-center'><b>Kredit</b></th>"
                        content += "<th width='7%' class='text-center'><b>Saldo Akhir</b></th>"
                        content += "</tr>"
                        content += "</thead>"
                        content += "<tbody>"                                            

                    for (b = 0; b < totDetData; b++) {                           
                        var debet = (data.data[b].debit === null) ? 0 : data.data[b].debit;
                        var kredit = (data.data[b].kredit === null) ? 0 : data.data[b].kredit;
                        // var debet_kemarin = (data.data[b].debit_kemarin === null) ? 0 : data.data[b].debit_kemarin;
                        // var kredit_kemarin = (data.data[b].kredit_kemarin === null) ? 0 : data.data[b].kredit_kemarin;
                        // var saldoAwal = ((data.data[b].debit_kemarin === null && data.data[b].kredit_kemarin === null)) ? data.data[b].saldo_awal : parseInt(kredit_kemarin) - parseInt(debet_kemarin); 
                        var saldoAwal = data.data[b].saldo_awal;
                        totDebet += parseInt(debet);                        
                        totKredit += parseInt(kredit);  
                        totSaldoAwal += saldoAwal;                        
                        
                        saldoAkhir = parseInt(saldoAwal) - parseInt(debet) + parseInt(kredit);
                        totSaldoAkhir += (saldoAkhir);  
                        
                        content += "<tr class='body' id='row_"+b+"'>"
                        content += "<td class='unit'>"+(b+1)+"</td>"                            
                        content += "<td class='text-center'>"+data.data[b].nomor_rekening+"</td>"
                        content += "<td class='unit'>"+data.data[b].nama+"</td>"                            
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(saldoAwal)+"</span></td>"
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(debet)+"</span></td>"
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(kredit)+"</span></td>"
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(saldoAkhir)+"</span></td>"
                        content += "</tr>";                                             
                    }

                    content += "</tbody>"
                    content += "<tfoot>"
                    content += "<tr class='table-primary'>"        
                    content += "<td colspan='3'><b>Jumlah</b></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totSaldoAwal)+"</b></span></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totDebet)+"</b></span></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totKredit)+"</b></span></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totSaldoAkhir)+"</b></span></td>"                
                    content += "</tr>"          
                    content += "</tfoot>"
                    content += "</table>"
                    content += "</div>";
                    $('#show_table').append(content);
                    //$("#myTable > tbody").append(content);
                    //totalDebetKredit();
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
                $("#per_tanggal").text("Per Tanggal : "+data.date ?? "");         
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

// function loadData2() {

//     data_table  = $('#example2').DataTable({            
//         processing: true,
//         searching: false,
//         paging: false,   
//         bInfo: false,     
//         ajax: {
//             "url": "" + base_url + '/getDataLapJB/lapJB',
//             'type': 'GET',
//             'dataType': 'JSON',
//             'error': function (xhr, textStatus, ThrownException) {                    
//                 sweetAlertDefault('Error loading data. Exception: '+ ThrownException + "\n" + textStatus, 'error', 2000 );
//             }
//         },

//         columns: [{
//             title: "No",            
//             data: "null",
//             visible: true,
//             sortable: true,
//             class: "text-center",
//             render: function ( data, type, full, meta ) {
//                 return  meta.row+1  ;
//             }
//         }, {
//             title: "Nomor Bukti",
//             data: "jurnal_no",
//             visible: true,
//             sortable: true,
//             class: ""
//         }, {
//             title: "Keterangan",
//             data: "jurnal_keterangan",
//             visible: true,
//             sortable: true,
//             class: ""
//         }, {
//             title: "Perkiraan",
//             data: "kode_perkiraan",
//             visible: true,
//             sortable: true,
//             class: "text-center"
//         }, {
//             title: "Debet",
//             data: function (data) { 
//                 if (data.id_jenis_transaksi === "1") {
//                     return "<span style='float: right'>"+convertToRupiahNoRp(data.jurnal_det_nominal)+"</span>";
//                 } else {
//                     return "<span style='float: right'>"+convertToRupiahNoRp("0")+"</span>";
//                 }
//             },
//             visible: true,
//             sortable: true,
//             class: ""        
//         }, {
//             title: "Kredit",
//             visible: true,
//             sortable: true,
//             class: "",
//             data: function (data) { 
//                 if (data.id_jenis_transaksi === "2") {
//                     return "<span style='float: right'>"+convertToRupiahNoRp(data.jurnal_det_nominal)+"</span>";
//                 } else {
//                     return "<span style='float: right'>"+convertToRupiahNoRp("0")+"</span>";
//                 }
//             }
//         }, {
//             title: "User",
//             data: "kode_perkiraan",
//             visible: true,
//             sortable: true,
//             class: ""
//         }
//         ],        
//     });            
// }


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