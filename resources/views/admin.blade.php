<!doctype html>
<html lang="en">
 <script>
        var base_url = window.location.origin;

    </script>

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!--favicon-->
	<link rel="icon" href="{{asset($lembaga[0]->logo_login) }}" type="image/png" />

	<!-- loader-->
	<link href="{{ asset('bank_stiep/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('bank_stiep/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('bank_stiep/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('bank_stiep/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('bank_stiep/css/icons.css') }}" rel="stylesheet">
	<link href="{{asset('bank_stiep/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">

	<title>ADMIN LOGIN - Lab Operasional Bank</title>
</head>

<body class="bg-lock-screen">
	<!-- wrapper -->
	<div class="wrapper">
		<div class="authentication-lock-screen d-flex align-items-center justify-content-center">
			<div class="card shadow-none bg-transparent">
				<div class="card-body p-md-5 text-center">
					
					<h2 class="text-white" id="clock"></h2>
					<h5 class="text-white">{{Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y')}}</h5>
					<div class="">						
						<img src="{{asset('bank_stiep/images/icons/user.png') }}" class="mt-5" width="120" alt="" />
					</div><br>
					<div class="form-body" id="form_login">
						<form id="form_login_proses" class="row g-2">@csrf											
							<div class="col-sm-12 mb-2 mt-2">								
								<input type="text" size="2" class="form-control form-control-sm border-end-0" id="inputChoosePassword" value="" name="username" placeholder="Enter Username">
							</div>											
							<div class="col-sm-12 mb-2 mt-2">								
								<div class="input-group" id="show_hide_password">
									<input type="password" class="form-control form-control-sm border-end-0" id="inputChoosePassword" value="" name="password" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide text-white'></i></a>
								</div>
							</div>											
							<div class="col-sm-12 mb-3 mt-3">
								<div class="d-grid">
									<div id="btn_login" class="btn btn-sm btn-primary">Login</div>
								</div>
							</div>
						</form>
					</div>					
				</div>
			</div>
		</div>
	</div>
	<!-- end wrapper -->
	
	<!-- Bootstrap JS -->
	<script src="{{ asset('bank_stiep/js/bootstrap.bundle.min.js') }}"></script>

	<!--plugins-->
	<script src="{{ asset('additional/js/global.js') }}"></script>
	<script src="{{ asset('bank_stiep/js/jquery.min.js') }}"></script>
	<script src="{{ asset('bank_stiep/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('bank_stiep/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('bank_stiep/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script src="{{asset('bank_stiep/plugins/sweetalert2/dist/sweetalert2.js') }}"></script>
	<script src="{{asset('bank_stiep/js/jquery.validate.min.js') }}"></script>	
	<script src="{{ asset('additional/js/admin.js') }}"></script>
    
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});				
		
	</script>
</body>

</html>
