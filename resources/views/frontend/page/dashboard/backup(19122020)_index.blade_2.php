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
  <div class="container">
    <div class="row push-down-100">
        <!-- left sidebar start -->
        <div class="col-md-3">
          <div class="travel_sidebar_wrapper">                  
            <aside class="widget widget_accordion">
              <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">                
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingFour">
                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#shift" aria-expanded="false"> Pilih Shift </a> </h4>
                  </div>
                  <div id="shift" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFour">
                    <div class="panel-body">
                      <aside class="widget widget_archive">
                        <ul>
                          <li><a href="javascript:;">January 2015</a></li>
                          <li><a href="javascript:;">February 2015</a></li>
                          <li><a href="javascript:;">March 2015</a></li>
                          <li><a href="javascript:;">April 2015</a></li>
                          <li><a href="javascript:;">May 2015</a></li>
                        </ul>
                      </aside>
                    </div>
                  </div>
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#accordion" href="#All_Categories" aria-expanded="true"> All Categories </a> </h4>
                  </div>
                  <div id="All_Categories" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                      <aside class="widget widget_categories"> 
                        <!-- Squared THREE -->
                        <ul>
                          <li>
                            <input type="checkbox" value="None" id="All" name="check" />
                            <label for="All">All (300)</label>
                          </li>
                          <li>
                            <input type="checkbox" value="None" id="Family" name="check" />
                            <label for="Family">Family (300)</label>
                          </li>
                          <li>
                            <input type="checkbox" value="None" id="Wild-Life" name="check" checked />
                            <label for="Wild-Life">Wild Life (250)</label>
                          </li>
                          <li>
                            <input type="checkbox" value="None" id="Honey-Moon" name="check" checked />
                            <label for="Honey-Moon">Honey Moon (150)</label>
                          </li>
                          <li>
                            <input type="checkbox" value="None" id="Beach" name="check" />
                            <label for="Beach">Beach (350)</label>
                          </li>
                          <li>
                            <input type="checkbox" value="None" id="Adventure" name="check" />
                            <label for="Adventure">Adventure (650)</label>
                          </li>
                        </ul>
                      </aside>
                    </div>
                  </div>
                </div>                      
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingFive">
                    <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#About_us" aria-expanded="false"> About us </a> </h4>
                  </div>
                  <div id="About_us" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                    <div class="panel-body">
                      <aside class="widget widget_text">
                        <p>Welcome to our blog!<br>
                          Modo rutrum a nec nibh. Vestibulum elementum suscipit orci sed consectetur. Morbi sapien nibh, vestibulum cursus molestie vitae.</p>
                      </aside>
                    </div>
                  </div>
                </div>                      
              </div>
            </aside>
          </div>
        </div>
        <!-- left sidebar end -->
        <!-- right main start -->
        <div class="col-md-9">
          <div class="tour_packages_right_section">                  
            <!-- sorting places section -->
            <div class="travel_loader"> <img src="{{ asset('frontend/images/icon/travel_loader.gif') }}" alt="" /> </div>
            <div class="full_width hotel_list_sorting">
              <div id="wisata_html">
                @foreach($wisatas as $wisata)
                <!--sort_list start -->
                <div class="sorting_places_wrap  list_sorting_view">
                  <div class="col-lg-5 col-md-5 col-sm-5 padding_none">
                    <div class="thumb_wrape">
                      <a href="#">
                        <img src="http://placehold.it/330x244" alt="hotel thumb">
                          <div class="overlay_title">
                            <h4>{{ $wisata->wisata_nama }}</h4>
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
                        <span class="flight_details">/ one way flight</span>
                        <div class="right_span"><span class="doller">$150</span><br/><span>/person</span></div>
                        <!-- review start -->
                        <div class="bottom_review_rating">
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
                        </div>
                        <!-- review End -->
                      </div>
                      <!-- title End -->
                      
                      <!-- flight schedule -->
                      <div class="flight_details_schedule">
                        <div class="take_off_landing">
                          <h5>Take Off</h5>
                          <p>20 Sep 2015 at 5.30am</p>
                        </div>
                        <div class="take_off_landing">
                          <h5>Landing</h5>
                          <p>22 Sep 2015 at 5.30am</p>
                        </div>
                      </div>
                      <!-- flight schedule -->
                      <div class="total_time_schedule">Total Time : 48 hours, 30 Minutes</div>
                      <div class="list_button">                        
                        <a href="{{route('book_date.index',['id_wisata' => Crypt::encrypt($wisata->wisata_id) ])}}" class="btns">
                          Book Now
                        </a>
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
