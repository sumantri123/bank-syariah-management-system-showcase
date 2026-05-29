@extends('frontend.layout_home.default')

@push('style')

      <!-- Aditional Style CSS Here -->

@endpush


@section('content')

<!--content body start-->
	<div id="content_wrapper">
        <!--page title Start-->
        <div class="page_title" style="background-image:url({{ asset('frontend/images/kenjeran/bg3.jpg')}});">        
          <ul>
            <li><a href="javascript:;">Tour Destination</a></li>
          </ul>
        </div>
        <!--page title end-->
        <div class="clearfix"></div>
        <div class="full_width destinaion_sorting_section">
          <div class="container">            
            <div class="row space_30">              
              <!-- right main start -->
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="tour_packages_right_section left_space_20">                  
                  <!--  sorting panel End -->
                  <!-- sorting places section -->
                  <br><br>
                  <div class="heading_team">
                    <h3>PILIH WISATA YANG AKAN DIKUNJUNGI</h3>
                  </div>
                  <div class="full_width hotel_list_sorting">
                      <div id="wisata_html">
				                @foreach($wisatas as $wisata)
                     <!--sort_list start -->
                    <div class="sorting_places_wrap  list_sorting_view">
                      <div class="col-lg-5 col-md-5 col-sm-5 padding_none">
                        <div class="thumb_wrape">
						 <a href="#">
                             @if($wisata->wisata_foto)
                                 <img src="{{$wisata->wisata_foto}}" alt="hotel thumb">
                             @else
                                 <img src="http://placehold.it/330x244" alt="hotel thumb">
                             @endif

                          <div class="overlay_title">
                            <h4>{{$wisata->wisata_nama}}</h4>
                          </div>
						  </a>
                        </div>
                      </div>
                      <div class="col-lg-7 col-md-7 col-sm-7">
                        <!-- Desc -->
                        <div class="flight_grid_desc flight_list_desc">
                          <!-- title start -->
                          <div class="bottom_title">
                            <h5><a href="#">{{ $wisata->wisata_nama }}</a></h5>
                            <!--<span class="flight_details">/ one way flight</span>-->
                            <!--<div class="right_span"><span class="doller">Rp. 80K - 100K</span><br/><span>/person</span></div>-->
							 <!-- review start -->
                              <!--<div class="bottom_review_rating">
                                <div class="rating_bottom">
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star"></i>
                                </div>
                            <span class="review_right">
                            <i class="fa fa-thumbs-up"></i> 52 Reviews
                            </span>
                          </div>-->
                          <!-- review End -->
                          </div>
                          <!-- title End -->
							<!-- flight schedule -->
							<div class="flight_details_schedule">
								<div class="take_off_landing">
									<h5>Jam Buka</h5>
									<p>{{ $wisata->jam_buka }}</p>
								</div>
								<div class="take_off_landing">
									<h5>Jam Tutupss</h5>
                                    <p>{{ $wisata->jam_tutup }}</p>
								</div>
							</div>
							<!-- flight schedule -->
							<div class="total_time_schedule">{{$wisata->wisata_alamat}}</div>
                            <p><b>{{$wisata->wisata_deskripsi}}</b></p>
							<div class="pull-right book_now_btn">
								<!--<a href="{{route('wisata_detail.index')}}" class="btn-green btn-travel">Read More</a>-->
								<a href="{{route('book_date.index',['id_wisata' => Crypt::encrypt($wisata->wisata_id) ])}}" class="btn-green btn-travel">Book Now</a>
								<!--<a href="{{route('book_date.index',['id_wisata' => ($wisata->wisata_id) ])}}" class="btn-green btn-travel">Book Now</a>-->
							</div>
                        </div><!--  Desc End -->
                      </div>
					</div><!--sort_list end-->
				@endforeach
                      </div>
                      <form>@csrf
                          &nbsp;
                      </form>
                    <!-- sorting places section -->
                    <!-- pagination section -->
                    <div id="pagination_button" class="full_width pagination_bottom">
                      <ul class="pagination">
                          <!--<li onclick="getWisataHeader(1);">11</li>-->
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                      </ul>
                    </div>
                    <!-- pagination section -->
                  </div>
                </div>
                <!-- right main start -->
              </div>
            </div>
		</div>
	</div><!--content body end-->

@endsection



@push('scripts')

	<script src="{{ asset('additional/js/wisata_frontend.js') }}"></script>
      <!-- Aditional Scripts Here -->

@endpush
