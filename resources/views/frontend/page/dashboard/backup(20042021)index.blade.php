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
    <br><br>
    <div class="heading_team">
      <h3></h3>      
      <h3>Lab Operasional Bank</h3>      
    </div>
    <div class="row push-down-20">        
        <!-- right main start -->
        <div class="col-md-12">
          <div class="post_wrapper">
            <!-- sorting places section -->            
            <div class="travel_loader"> <img src="{{ asset('frontend/images/icon/travel_loader.gif') }}" alt="" /> </div>
            <div class="full_width hotel_list_sorting">
              <div id="wisata_html">
                
              </div>
              <form>@csrf
                  &nbsp;
              </form>                                   
              <!-- sorting places section -->
              <!-- pagination section -->
              <div id="pagination_button" class="full_width pagination_bottom">
                <ul class="pagination">
                  <!--<li onclick="getWisataHeader(1);">11</li>-->
                  <!--<li><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">4</a></li>
                  <li><a href="#">5</a></li>-->
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

<!-- (Ajax Modal)-->
<div class="modal fade" id="page_model_view_data">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-body" style="height:500px; overflow:auto;">
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div> 
@endsection



@push('scripts')

	<script src="{{ asset('additional/js/wisata_frontend.js') }}"></script>
      <!-- Aditional Scripts Here -->

@endpush
