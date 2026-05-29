var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {           
    $('#contentJB').hide();
    //loadData();
    //disableEntry();    
});    

$('button#btn_print').on('click', function () {                      
                
    var myModalHorizontalprint = document.getElementById('myModalHorizontalprint');
    var popupWin = window.open('', '_blank', 'left=0,top=0,width=1000,height=700,status=0');
    popupWin.document.open();
    popupWin.document.write('<html><body width="100%" onload="window.print()">' + myModalHorizontalprint.innerHTML + '</html>');
    popupWin.document.close();
    
});

$('button#btn_search').on('click', function () {                

    var dateCari = document.getElementById("tgl_cari").value;       
    $('#headerJB').hide("slow");
    $('#contentJB').show("slow");    
    loadData(dateCari);
    
});

$('button#btn_back').on('click', function () {                      
    
    $('#contentJB').hide("slow");
    $('#headerJB').show("slow");
    $('#myTable').remove();
    
});

function loadData(dateCari){   
    
    $.ajax({
        type: 'POST',
        url: "" + base_url + "/getDataMon/lapMonRek",        
        dataType: 'JSON',
        data: {
            _token: CSRF_TOKEN,
            //search: request.term
            date: dateCari
        },
        // beforeSend: function () {
        //     sweetAlertLoading('Memproses');
        // },
        success: function (data) {
            if (data.status == 'oke') {                                
                if(data.total >0){
                    var totDetData = data.data.length;
                    var totDebet = 0;
                    var totKredit = 0;
                    var b;
                    var content = "<table id='myTable' border='1' class='table table-striped table-bordered'>"
                        content += "<thead>"
                        content += "<tr class='table-primary'>"
                        content += "<th width='3%' class='text-center'><b>No.</b></th>"
                        content += "<th width='10%' class='text-center'><b>Perkiraan</b></th>"
                        content += "<th width='25%' class='text-center'><b>Keterangan</b></th>"
                        content += "<th width='15%' class='text-center'><b>Nomor Bukti</b></th>"                        
                        content += "<th width='10%' class='text-center'><b>Debet</b></th>"
                        content += "<th width='10%' class='text-center'><b>Kredit</b></th>"
                        content += "<th width='7%' class='text-center'><b>User</b></th>"
                        content += "</tr>"
                        content += "</thead>"
                        content += "<tbody>"
                    //alert(totDetData);
                    for (b = 0; b < totDetData; b++) {
                        // // alert(data.data[b].jurnal_det_nominal);
                        var debet = ((data.data[b].id_jenis_transaksi)=="1") ? data.data[b].jurnal_det_nominal :"0";
                        var kredit = ((data.data[b].id_jenis_transaksi)=="2") ? data.data[b].jurnal_det_nominal :"0";
                        totDebet += parseInt(debet);
                        totKredit += parseInt(kredit);

                        content += "<tr class='body' id='row_"+b+"'>"
                        content += "<td class='unit'>"
                        content += b+1
                        content += "</td>"
                        content += "<td class='text-center'>"+data.data[b].kode_perkiraan+"</td>"
                        content += "<td class='text-left'>"+data.data[b].jurnal_keterangan+"</td>"
                        content += "<td class='unit'>"+data.data[b].jurnal_no+"</td>"                        
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(debet)+"</span></td>"
                        content += "<td><span style='float: right;'>"+convertToRupiahNoRp(kredit)+"</span></td>"                    
                        content += "<td>"+data.data[b].user_record+"</td>"
                        content += "</tr>";
                    }

                    content += "</tbody>"
                    content += "<tfoot>"
                    content += "<tr class='table-primary'>"        
                    content += "<td colspan='4'><b>TOTAL D/K</b></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totDebet)+"</b></span></td>"                
                    content += "<td><span style='float: right;'><b>"+convertToRupiahNoRp(totKredit)+"</b></span></td>"                
                    content += "<td></td>"
                    content += "</tr>"        
                    content += "</tfoot>";
                    content += "</table>"
                    $('#show_table').append(content);
                    //$("#myTable > tbody").append(content);
                    //totalDebetKredit();
                } else {
                    var content = "<div id='myTable' class='alert alert-danger border-0 bg-danger alert-dismissible fade show py-2'>"
                        content += "<div class='d-flex align-items-center'>"
                        content += "<div class='font-35 text-white'><i class='bx bxs-message-square-x'></i></div>"                            
                        content += "<div class='ms-3'>"
                        content += "<h6 class='mb-0 text-white'>Note :</h6>"
                        content += "<div class='text-white'>Tidak Ada Transaksi Dengan Kode Perkiraan 309xxx Pada Tanggal Yang Anda Pilih</div>"
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