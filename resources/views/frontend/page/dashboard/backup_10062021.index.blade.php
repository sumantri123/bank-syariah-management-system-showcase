@extends('frontend.layout_home.default')

@push('style')

      <!-- Aditional Style CSS Here -->

@endpush


@section('content')

<h6 class="mb-0 text-uppercase">Lab Operasional Bank {{ Auth::user()->kelas_id }} - {{ session('tanggal')}}</h6>
<hr/>

<div class="row"> 	
	<div class="col-sm-3">                        
		<div class="card radius-10 bg-primary">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>
						<p class="mb-0 font-16 text-white">Tanggal</p>
						<h4 class="my-1 text-white">{{$data['now']}}</h4>
						<p class="mb-0 font-14 text-white">Welcome {{ Session::get('login_as') }}</p>
					</div>
					<div class="widgets-icons bg-white text-primary ms-auto"><i class='bx bx-calendar-week'></i>
					</div>
				</div>
			</div>
		</div>      
	</div>
	<div class="col-sm-3">                        
		<div class="card radius-10 bg-dark">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>                                                                                
						<p class="mb-0 font-16 text-white">{{$LNilaiTukar[0]->kurs_nama}}</p>
							<h4 class="my-1 text-white">{{$LNilaiTukar[0]->kurs_jual.'/'.$LNilaiTukar[0]->kurs_beli}}</h4>
						<p class="mb-0 font-14 text-white">Kelas : {{$LKelas[0]->name}}</p>
					</div>
					<div class="widgets-icons bg-white text-dark ms-auto"><i class="fadeIn animated bx bx-money"></i></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">                        
		<div class="card radius-10 bg-success">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>                                                                                
						<p class="mb-0 font-16 text-white">{{$LNilaiTukar[1]->kurs_nama}}</p>
						<h4 class="my-1 text-white">{{$LNilaiTukar[1]->kurs_jual.'/'.$LNilaiTukar[1]->kurs_beli}}</h4>
						<p class="mb-0 font-14 text-white">Kelas : {{$LKelas[0]->name}}</p>
					</div>
					<div class="widgets-icons bg-white text-success ms-auto"><i class="fadeIn animated bx bx-money"></i></div>
				</div>
			</div>
		</div>
	</div>	
	<div class="col-sm-3">                        
		<div class="card radius-10 bg-danger">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div>                                                                                
						<p class="mb-0 font-16 text-white">{{$LNilaiTukar[2]->kurs_nama}}</p>
						<h4 class="my-1 text-white">{{$LNilaiTukar[2]->kurs_jual.'/'.$LNilaiTukar[2]->kurs_beli}}</h4>
						<p class="mb-0 font-14 text-white">Kelas : {{$LKelas[0]->name}}</p>
					</div>
					<div class="widgets-icons bg-white text-danger ms-auto"><i class="fadeIn animated bx bx-money"></i></div>
				</div>
			</div>
		</div>
	</div>	
</div>
@endsection

@push('scripts')	

@endpush
