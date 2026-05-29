<!-- Bootstrap JS -->
<script src="{{asset('bank_stiep/js/bootstrap.bundle.min.js') }}"></script>
<!--plugins-->
<script src="{{asset('bank_stiep/js/jquery.min.js') }}"></script>
<script src="{{asset('bank_stiep/js/jquery.validate.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/metismenu/js/metisMenu.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/chartjs/js/Chart.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/sparkline-charts/jquery.sparkline.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/jquery-knob/excanvas.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/jquery-knob/jquery.knob.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/sweetalert2/dist/sweetalert2.js') }}"></script>
<script src="{{asset('bank_stiep/js/index.js') }}"></script>
<script src="{{asset('additional/js/global.js') }}"></script>
<script src="{{asset('bank_stiep/js/app.js') }}"></script>
<script src="{{asset('bank_stiep/js/plugins.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{asset('bank_stiep/js/pace.min.js') }}"></script>
<!-- Tabel -->
<script src="{{asset('bank_stiep/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<!-- Date Time -->
<script src="{{asset('bank_stiep/plugins/datetimepicker/js/legacy.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/datetimepicker/js/picker.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/datetimepicker/js/picker.time.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/datetimepicker/js/picker.date.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/bootstrap-material-datetimepicker/js/moment.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js') }}"></script>

<!-- Notification -->
<script src="{{asset('bank_stiep/plugins/notifications/js/lobibox.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/notifications/js/notifications.min.js') }}"></script>
<script src="{{asset('additional/js/notification-custom-script.js') }}"></script>

<!-- AutoComplete -->
<script src="{{asset('bank_stiep/plugins/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/jquery-ui-1.12.1/jquery-ui.js') }}"></script>
<!-- <script src="{{asset('bank_stiep/plugins/smart-wizard/js/jquery.smartWizard.min.js') }}"></script> -->

<script>
	
	$(document).ready(function () {			
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		if (typeof (Storage) !== "undefined") {
			
			if(localStorage.getItem("menu")!=null){
				var urlx = localStorage.getItem("menu");
				
				var $myElement = $('[data-href="'+urlx+'"]');
				var $parent = $myElement.parent();
				$parent.attr('class', "mm-active");

				$.ajax({
					url: "" + base_url + urlx,
					type: 'get',
					cache: false,
					data: {
						_token: CSRF_TOKEN,                
					},
					beforeSend: function (){
						localStorage.removeItem("menu");
					},
					success: function (data) {					
						$('.isiContent').html(data.html);
						localStorage.setItem("menu", urlx);
						
					},
			
					error: function (xhr, status, error, xmlhttprequest, textstatus, message) {						
						error_noti(xmlhttprequest+"/"+textstatus+"/"+message);
					}
				});
			}

		} else {
			document.getElementsByClassName("isiContent").innerHTML = "Sorry, your browser does not support Web Storage...";
		}
		
		$('.action').click(function(event){			      			      
			var urlx = $(this).attr('data-href');		      

			$.ajax({
				url: "" + base_url + urlx,
				type: 'get',
				cache: false,
				data: {
					_token: CSRF_TOKEN,                
				},	
				beforeSend: function (){
					localStorage.removeItem("menu");
				},					
				success: function (data) {													
					$('.isiContent').html(data.html);
					localStorage.setItem("menu", urlx);
				},
		
				error: function (xhr, status, error, xmlhttprequest, textstatus, message) {						
					alert(xmlhttprequest+"/"+textstatus+"/"+message);																						
				}
			});				
		});			

		$.ajax({
            type: 'get',
            url: "" + base_url + "/kursFooter",        
            dataType: 'JSON',
            data: {
                _token: CSRF_TOKEN,                
            },
            success: function (data) {
                if (data.status == 'oke') {                    

					var totalData = data.data.length;					
					for (b = 0; b < totalData; b++) {
						var content_b = "" 
						content_b = data.data[b].kurs_nama+" : "+data.data[b].kurs_beli+"/"+data.data[b].kurs_jual;
						$('#kurs_'+b).text(content_b);                   	
					}					
					
					$('#kelas').text("Kelas : "+data.dataKelas[0].name);                   	
                } else {
                    error_noti('Data Tidak Tersedia');                     
                }
            },
    
            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
				
            }
        });						

		$('#login_as_edp').on('click', function () {
            login_as("EDP");
        });
		
		$('#login_as_import').on('click', function () {
			login_as("Import");
		});
		
		$('#login_as_export').on('click', function () {
			login_as("Export");
		});
		$('#login_as_ao').on('click', function () {
			login_as("Account Officer");
		});
		$('#login_as_akuntansi').on('click', function () {
			login_as("Akuntansi");
		});
		$('#login_as_admin_kredit').on('click', function () {
			login_as("Admin Kredit");
		});
		$('#login_as_kliring').on('click', function () {
			login_as("Kliring");
		});
		$('#login_as_deposito').on('click', function () {
			login_as("Deposito");
		});
		$('#login_as_transfer').on('click', function () {
			login_as("Transfer");
		});
		$('#login_as_giro').on('click', function () {
			login_as("Giro");
		});
		$('#login_as_teller').on('click', function () {
			login_as("Teller");
		});
		$('#login_as_cs').on('click', function () {
			login_as("Customer Service");
		});
		
		function login_as(id){
		
			var action_url = "" + base_url + "/login_as";
			  $.ajax({
                type: 'POST',
                url: action_url,
                dataType: 'JSON',
				data: {
					_token: $('meta[name="csrf-token"]').attr('content'),
					sebagai: id,
				},

                success: function (data) {
                    if (data.status == 'insert_successful') {
                        success_noti('Switch user is successfull');
						localStorage.setItem("menu", "/");
						window.location.replace("/");
                    } else {
					error_noti('Gagal (Kesalahan Sistem)');
                    }
                },

                error: function (xmlhttprequest, textstatus, message) {
                    error_noti('Koneksi Ke Server Gagal, '+message);
                }

            });
		}			
	});
</script>	

@stack('scripts')
