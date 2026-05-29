var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {    
    $('#searchGrup').hide("slow");
    addRow();        
    disableEntry();    
});    

$('button#btn_search').on('click', function () {                          
    disableClearEntry();    
    $("#id_rekening").select2().select2('val','""');
    $("#id_rekening").select2({ width: "100%" });
    $("#rek_lawan_perk").select2().select2('val','""');   
    $('#searchGrup').show("slow");
    $('#search').attr('readOnly', false);
    $('#search').focus();    
    $('#tgl').attr('disabled', true);         
    $('#myTable tr.body').remove();    
    $('#myTable2 tr.body').remove();        
    //totalDebetKredit();
    
});

$('button#btn_new').on('click', function () {  
    
    enableEntry();  
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd; 
    $('#no_bukti').focus();   
    $('#tgl').attr('readOnly', true);        
    $('#tgl').val(today);      
    $("#id_rekening").select2().select2('val','""');
    $("#rek_lawan_perk").select2().select2('val','""');                    
    $("#id_rekening").select2({ width: "100%" });    
    $('#id_transaksi').val("");
    $('#method_field').val("POST");      
    $('#search').attr('readOnly', true);
    $('#searchGrup').hide("slow"); 
    $('#myTable tr.body').remove();    
    $('#myTable2 tr.body').remove();    
    
   
    
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
    var id = document.getElementById("idTr").value;
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

function addRow(){
    
    var content2 = "<label for='inputCity' class='form-label' style='color:blue; font-weight:bold'>Transaksi</label>"
        content2 += "<table id='myTable2' border='1' class='classTable table-sm'>"
        content2 += "<thead>"
        content2 += "<tr>"
        content2 += "<th width='30%' style='font-size:12px' class='text-center'><b>Slip</b></th>"        
        content2 += "<th width='15%' style='font-size:12px' class='text-center'><b>Rek Nomor</b></th>"
        content2 += "<th width='10%' style='font-size:12px' class='text-center'><b>Nasabah</b></th>"
        content2 += "<th width='10%' style='font-size:12px' class='text-center'><b>Kd</b></th>"
        content2 += "<th width='10%' style='font-size:12px' class='text-center'><b>Nominal</b></th>"
        content2 += "</tr>"
        content2 += "</thead>"
        content2 += "<tbody>"
        content2 += "<tr class='body'>"
        content2 += "<td class='unit'></td>"                        
        content2 += "<td class='text-left'></td>"
        content2 += "<td class='qty'></td>"
        content2 += "<td class='unit'></td>"
        content2 += "<td class='qty'></td>"
        content2 += "</tr>"
        content2 += "</tbody>"        
    content2 += "</table>"
    $('#show_table2').append(content2);  

    var content = "<label for='inputCity' class='form-label' style='color:blue; font-weight:bold'>Transaksi</label>"
        content += "<table id='myTable' border='1' class='classTable table-sm'>"
        content += "<thead>"
        content += "<tr>"
        content += "<th width='30%' style='font-size:12px' class='text-center'><b>No.Bukti</b></th>"        
        content += "<th width='50%' style='font-size:12px' class='text-center'><b>Rek Nomor</b></th>"
        content += "<th width='10%' style='font-size:12px' class='text-center'><b>Debit</b></th>"
        content += "<th width='10%' style='font-size:12px' class='text-center'><b>Kredit</b></th>"
        content += "</tr>"
        content += "</thead>"
        content += "<tbody>"
        content += "<tr class='body'>"
        content += "<td class='unit'></td>"                        
        content += "<td class='text-left'></td>"
        content += "<td class='unit'></td>"
        content += "<td class='qty'></td>"
        content += "</tr>"
        content += "</tbody>"        
    content += "</table>"
    $('#show_table').append(content);                                  
    
}

$("#search").keypress(function (e) {
    if(e.keyCode==13){
        var kode = $('#search').val();
        var bagian = $('#bagian').val();
        $('#search').attr('readOnly', true);
        $('#method_field').val("SEARCH");    
        
        $.ajax({
            type: 'post',
            url: "" + base_url + "/search/tranDep",
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
                    
                    $('#no_bukti').val(data.jbNo);
                    $('#idTr').val(data.jbId);
                    $('#tgl').val(data.jbTgl);
                    $('#id_perkiraan1').val(data.jbIdPerkiraan1);
                    $('#keterangan').val(data.jbKet);
                    $('#nominal').val(convertToRupiahNoRp(data.jbNominal));
                    $("#id_rekening").val(data.jbIdRekening).trigger('change');    
                    //$("#id_rekening").select2().select2('val',''+data.jbIdRekening+'');
                    $("#id_rekening").select2({ width: "100%" });                                        
                    $("#rek_lawan_perk").val(data.jbRekLawan).trigger('change');                                        
                    $('#kode_perkiraan').val(data.jbRekKodeLawan);
                    $('#id_transaksi').val(data.jbIdTransaksi);                    

                    var content2;
                    var kode = (data.jbIdTransaksi==1) ? "D":"K";
                    content2 += "<tr class='body'>"
                    content2 += "<td class='unit' style='font-size:12px'>"+data.jbNo+"</td>"                                                            
                    content2 += "<td class='unit' style='font-size:12px'>"+data.jbNoRekening+"</td>"
                    content2 += "<td class='unit' style='font-size:12px'>"+data.jbNama+"</td>"
                    content2 += "<td class='qty' style='font-size:12px'>"+kode+"</td>"
                    content2 += "<td class='unit' style='font-size:12px'>"+convertToRupiahNoRp(data.jbNominal)+"</td>"                                        
                    content2 += "</tr>";

                    var content;
                    var debet = ((data.jbIdTransaksi)=="1") ? data.jbNominal :"0";
                    var kredit = ((data.jbIdTransaksi)=="2") ? data.jbNominal :"0";

                    var debet1 = ((data.jbIdTransaksi1)=="1") ? data.jbNominal :"0";
                    var kredit1 = ((data.jbIdTransaksi1)=="2") ? data.jbNominal :"0";

                    content += "<tr class='body'>"
                    content += "<td class='unit' style='font-size:12px'>"+data.jbRekKodeLawan+"</td>"                                                            
                    content += "<td class='unit' style='font-size:12px'>"+data.jbRekNamaPer+"</td>"
                    content += "<td class='qty' style='font-size:12px'>"+convertToRupiahNoRp(debet)+"</td>"
                    content += "<td class='unit' style='font-size:12px'>"+convertToRupiahNoRp(kredit)+"</td>"                                        
                    content += "</tr>";
                    content += "<tr class='body'>"
                    content += "<td class='unit' style='font-size:12px'>"+data.jbRekKodeLawan1+"</td>"                                                            
                    content += "<td class='unit' style='font-size:12px'>"+data.jbRekNamaPer1+"</td>"
                    content += "<td class='qty' style='font-size:12px'>"+convertToRupiahNoRp(debet1)+"</td>"
                    content += "<td class='unit' style='font-size:12px'>"+convertToRupiahNoRp(kredit1)+"</td>"                                        
                    content += "</tr>";                    
                    
                     $("#myTable > tbody").append(content);
                     $("#myTable2 > tbody").append(content2);
                
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

function getval(sel){                
    var selected = sel.value;   
    var transaksi = document.getElementById("id_transaksi").value;   
    var keteranganTransaksi = (transaksi==1) ? "Pencairan Rek":"Penempatan Rek";      

    if(selected !=""){
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/GetPerkiraanDep1/" + selected,
            dataType: 'JSON',            
            success: function (data) {
                if (data.status == 'oke') {                    
                    $('#id_perkiraan1').val(data.idPerkiraan);  	                
                    $('#kode_perkiraan1').val(data.kodePerkiraan);  	                
                    $('#nama_perkiraan1').val(data.namaPerkiraan);  	                
                    $('#no_rekening').val(data.noRekening);
                    $('#nama').val(data.nama);
                    $('#nominal').val(data.saldoDeposito);
                    $('#keterangan').val(keteranganTransaksi+" "+data.noRekening); 
                } else if (data.status == 'null') {
                    info_noti('Tidak Ada Data Rekening');	                
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
            url: "" + base_url + "/GetPerkiraanDep2/" + selected,
            dataType: 'JSON',            
            success: function (data) {
                if (data.status == 'oke') {                    
                    $('#kode_perkiraan2').val(data.kodePerkiraan);  	                
                    $('#nama_perkiraan2').val(data.namaPerkiraan);  	                                   	                
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

function getval3(sel){                
    var selected = sel.value;   
    var idRek = document.getElementById("id_rekening").value;     
    var noRek = document.getElementById("no_rekening").value;     
    var keteranganTransaksi = (selected==1) ? "Pencairan Rek":"Penempatan Rek";
     
    if(selected==1){ // cek apakah pencairan dana deposito
        if(idRek ==""){                        
            info_noti('Anda Belum Memilih Rekening Deposito');        
            $('#nominal').val("");
        }
        $('#nominal').attr('readOnly', true);       
    } else {
        $('#nominal').attr('readOnly', false);
    }
    $('#keterangan').val(keteranganTransaksi+" "+noRek); 
}

function formatRupiah(y){
    var query = y.value;
    
    $( "#nominal" ).on('keyup',(function (event) {
        $(this).val(function (index, value) {
            return value.replace(/(?!\.)\D/g, "").replace(/(?<=\..*)\./g, "").replace(/(?<=\.\d\d).*/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    }));    
}

function deleteProses(id) {        

    $.ajax({
        type: 'GET',
        url: "" + base_url + "/delete/tranDep/" + id,
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
                $('#myTable tr.body').remove();    
                $('#myTable2 tr.body').remove();    
                $('#idTr').val("");
                $("#id_rekening").select2().select2('val','""');
                $("#rek_lawan_perk").select2().select2('val','""');                    
                $("#id_rekening").select2({ width: "100%" });    
                $('#id_transaksi').val(""); 
                disableClearEntry();
                //$('#myTable tr.body').remove();
                //totalDebetKredit();
            } else if (data.status == 'delete_failed') {
                error_noti('Data Gagal Dihapus');
                //totalDebetKredit();
            } else {
                error_noti('Data Gagal Dihapus (Kesalahan Sistem)');
                //totalDebetKredit();
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });
}

function insertUpdateInduk() {

    var form = $('#formEntry');
    $('#myTable tr.body').remove();    
    $('#myTable2 tr.body').remove();    
    if (form.valid() == true) {

        var method = $('#method_field').val();                    
        var action_url = "" + base_url + "/tranDep";  
        var action_type = "Tambah";                      
        if (method === "PUT") {
            action_url = "" + base_url + "/tranDep/" + $('#idTr').val();
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
                    $('#idTr').val(data.id);                     
                    $('#method_field').val("PUT");
                    disableEntry();

                    var content2;
                    var kode = (data.jbIdTransaksi==1) ? "D":"K";
                    content2 += "<tr class='body'>"
                    content2 += "<td class='unit' style='font-size:12px'>"+data.jbNo+"</td>"                                                            
                    content2 += "<td class='unit' style='font-size:12px'>"+data.jbNoRekening+"</td>"
                    content2 += "<td class='unit' style='font-size:12px'>"+data.jbNama+"</td>"
                    content2 += "<td class='qty' style='font-size:12px'>"+kode+"</td>"
                    content2 += "<td class='unit' style='font-size:12px'>"+convertToRupiahNoRp(data.jbSaldoAwal)+"</td>"                                        
                    content2 += "</tr>";
                    
                    var debet = ((data.jbIdTransaksi)=="1") ? data.jbNominal :"0";
                    var kredit = ((data.jbIdTransaksi)=="2") ? data.jbNominal :"0";

                    var debet1 = ((data.jbIdTransaksi1)=="1") ? data.jbNominal :"0";
                    var kredit1 = ((data.jbIdTransaksi1)=="2") ? data.jbNominal :"0";

                    var content;                    
                    content += "<tr class='body'>"
                    content += "<td class='unit' style='font-size:12px'>"+data.jbRekKodeLawan+"</td>"                                                            
                    content += "<td class='unit' style='font-size:12px'>"+data.jbRekNamaPer+"</td>"
                    content += "<td class='qty' style='font-size:12px'>"+convertToRupiahNoRp(debet)+"</td>"
                    content += "<td class='unit' style='font-size:12px'>"+convertToRupiahNoRp(kredit)+"</td>"                                        
                    content += "</tr>";
                    content += "<tr class='body'>"
                    content += "<td class='unit' style='font-size:12px'>"+data.jbRekKodeLawan1+"</td>"                                                            
                    content += "<td class='unit' style='font-size:12px'>"+data.jbRekNamaPer1+"</td>"
                    content += "<td class='qty' style='font-size:12px'>"+convertToRupiahNoRp(debet1)+"</td>"
                    content += "<td class='unit' style='font-size:12px'>"+convertToRupiahNoRp(kredit1)+"</td>"                                        
                    content += "</tr>";                              
                    
                    $("#myTable > tbody").append(content);
                    $("#myTable2 > tbody").append(content2);
                                                                            
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
        id_rekening: {required: true},
        id_transaksi: {required: true},
        nominal: {required: true},
        tgl: {required: true},        
        rek_lawan_perk: {required: true},
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