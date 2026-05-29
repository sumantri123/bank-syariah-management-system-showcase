var data_table;

$(document).ready(function () {
    loadData();

    $('button#tambah').on('click', function () {        
//       clearModal();                 
         $('#modal_label').text('Form Tambah Data');
         $('#method_field').val("POST");
         $(".modal-form").modal('show');         
    });

    $('button#btn_simpan').on('click', function () {        
        insertUpdateProses();
    });    
        
});            

    function loadData() {
            data_table = $('#example2').DataTable({
            processing: true,
            lengthChange: false,
            initComplete: function() {
                data_table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
                $("#example2").show();
            },
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
            ajax: {
                "url": "" + base_url + '/getDataJson/nasabahIndividu',
                'type': 'GET',
                'dataType': 'JSON',
                'error': function (xhr, textStatus, ThrownException) {
                    error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
                }
            },
            columns: [
            {
                title: "No cif ",
                data: "cif",
                visible: true,
                sortable: true,
                class: "text-center"
            }, {
                title: "Nama Lengkap",
                data: "nama",                
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Tanggal Lahir",
                data: "tanggal_lahir",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Kewarganegaraan",
                data: "kewarganegaraan",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Jenis Identitas",
                data: "jenis_identitas",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "No. Identitas",
                data: "no_identitas",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Alamat",
                data: "alamat_ktp",
                visible: true,
                sortable: true,
                class: ""
            }],
		});            
    }    

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