var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {           
    $('#contentJB').hide();
    //loadData();
    disableEntry();
    //totalDebetKredit();    
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
    var kodeBagian = document.getElementById("bagian").value;   
    var form = $('#formEntry');
    
    if (form.valid() == true) {    
        $('#headerJB').hide("slow");
        $('#contentJB').show("slow");    
        loadData(dateCari,kodeBagian);
    } else {
        error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
    }
    
});

$('button#btn_back').on('click', function () {                      
    
    $('#contentJB').hide("slow");
    $('#headerJB').show("slow");
    $('#myTable').remove();
    
});

function loadData(dateCari,kodeBagian){   
    
    $.ajax({
        type: 'POST',
        url: "" + base_url + "/getDataLapJB/lapJB",        
        dataType: 'JSON',
        data: {
            _token: CSRF_TOKEN,
            //search: request.term
            date: dateCari,
            kode: kodeBagian,
        },
        beforeSend: function (){
            $("#loading").show(1000);
        },
        success: function (data) {
            $("#loading").hide();
            if (data.status == 'oke') {                                
                if(data.total >0){
                    var totDetData = data.data.length;
                    var totDebet = [];
                    var totKredit = [];
                    var b;

                    var content = "<div style='overflow-x:auto;'>"
                        content += "<table id='myTable' border='1' class='table table-striped table-bordered'>"
                        content += "<thead>"
                        content += "<tr class='table-primary'>"
                        content += "<th width='3%' class='text-center'><b>No.</b></th>"
                        content += "<th width='20%' class='text-center'><b>Nomor Bukti</b></th>"
                        content += "<th width='40%' class='text-center'><b>Keterangan</b></th>"
                        content += "<th width='10%' class='text-center'><b>Perkiraan</b></th>"
                        content += "<th width='10%' class='text-center'><b>Debet</b></th>"
                        content += "<th width='10%' class='text-center'><b>Kredit</b></th>"
                        content += "<th width='7%' class='text-center'><b>User</b></th>"
                        content += "</tr>"
                        content += "</thead>"
                        content += "<tbody>"
                    
                    var a=0;                    
                    totDebet[0] = 0;
                    totKredit[0] = 0;
                    for (b = 0; b < totDetData; b++) {
                        // // alert(data.data[b].jurnal_det_nominal);
                        var c = ((b+1) == totDetData) ? b:b+1; // cek data selanjutnya
                        var debet = ((data.data[b].id_jenis_transaksi)=="1") ? data.data[b].jurnal_det_nominal :"0";
                        var kredit = ((data.data[b].id_jenis_transaksi)=="2") ? data.data[b].jurnal_det_nominal :"0";
                        totDebet[a] += parseInt(debet);                        
                        totKredit[a] += parseInt(kredit);  

                        // totDebet += parseInt(debet);
                        // totKredit += parseInt(kredit);

                        content += "<tr class='body' id='row_"+b+"'>"
                        content += "<td class='unit'>"
                        content += b+1
                        content += "</td>"
                        content += "<td class='unit'>"
                        content += data.data[b].kode_transaksi+"."+data.data[b].jurnal_no+"."+data.data[b].jurnal_tanggal
                        content += "</td>"
                        content += "<td class='text-left'>"
                        content += data.data[b].jurnal_keterangan
                        content += "</td>"
                        content += "<td class='text-center'>"+data.data[b].kode_perkiraan+"</td>"
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(debet)+"</span></td>"
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(kredit)+"</span></td>"                    
                        content += "<td>"+data.data[b].user_record+"</td>"
                        content += "</tr>";

                        if(data.data[b].kode_transaksi != data.data[c].kode_transaksi){                                                                
                            content += "<tr class='body table-primary' id='row_"+b+"'>"
                            content += "<td colspan='4'><b>Jumlah</b></td>"
                            content += "<td><b><span style='float: right;'>"+convertToRupiahNoRp(parseInt(totDebet[a]))+"</span></b></td>"
                            content += "<td><b><span style='float: right;'>"+convertToRupiahNoRp(parseInt(totKredit[a]))+"</span></b></td>"
                            content += "<td class='text-center'><b></b></td>"                            
                            content += "</tr>";

                            a+1;
                            totDebet[a] = 0;
                            totKredit[a] = 0;
                        }
                    }

                    content += "</tbody>"
                    content += "<tfoot>"
                    content += "<tr class='table-primary'>"        
                    content += "<td colspan='4'><b>Jumlah</b></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totDebet)+"</b></span></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totKredit)+"</b></span></td>"                
                    content += "<td></td>"
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
                        content += "<div class='text-white'>Tidak Ada Jurnal Bagian Pada Tanggal Yang Anda Pilih</div>"
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