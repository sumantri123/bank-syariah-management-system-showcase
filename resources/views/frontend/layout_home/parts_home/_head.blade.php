<script>
    var base_url = window.location.origin;

</script>
<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!--favicon-->
	<link rel="icon" href="{{ URL::asset(session("logoUHW")) }}" type="image/png" />
	<!--plugins-->	

	<link href="{{asset('bank_stiep/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
	<link href="{{asset('bank_stiep/plugins/notifications/css/lobibox.min.css') }}" rel="stylesheet"/>
	<link href="{{asset('bank_stiep/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{asset('bank_stiep/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
	<link href="{{asset('bank_stiep/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
	<link href="{{asset('bank_stiep/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{asset('bank_stiep/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />	

	<link href="{{asset('bank_stiep/plugins/jquery-ui-1.12.1/jquery-ui.min.css') }}" rel="stylesheet" />
	<link href="{{asset('bank_stiep/plugins/jquery-ui-1.12.1/jquery-ui.css') }}" rel="stylesheet" />
	
	<!-- Date -->
	<link href="{{asset('bank_stiep/plugins/datetimepicker/css/classic.css') }}" rel="stylesheet" />
	<link href="{{asset('bank_stiep/plugins/datetimepicker/css/classic.time.css') }}" rel="stylesheet" />
	<link href="{{asset('bank_stiep/plugins/datetimepicker/css/classic.date.css') }}" rel="stylesheet" />
	<link rel="stylesheet" href="{{asset('bank_stiep/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css') }}">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

	<!-- Table -->
	<link href="{{asset('bank_stiep/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />

	<!-- loader-->
	<link href="{{asset('bank_stiep/css/pace.min.css') }}" rel="stylesheet" />	
	<!-- Bootstrap CSS -->
	<link href="{{asset('bank_stiep/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{asset('bank_stiep/css/app.css') }}" rel="stylesheet">
	<link href="{{asset('bank_stiep/css/icons.css') }}" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{asset('bank_stiep/css/dark-theme.css') }}" />
	<link rel="stylesheet" href="{{asset('bank_stiep/css/semi-dark.css') }}" />
	<link rel="stylesheet" href="{{asset('bank_stiep/css/header-colors.css') }}" />
	
	<link href="{{asset('bank_stiep/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">	

	<title>{{ session("subtitle") }}</title>
</head>
