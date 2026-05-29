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
                <h3>PILIHAN WISATA</h3>
            </div>
            <div class="row push-down-20">
                <!-- right main start -->
                <div class="col-md-12">
                    <div class="post_wrapper">
                        <!-- sorting places section -->
                        <div class="travel_loader"> <img src="{{ asset('frontend/images/icon/travel_loader.gif') }}" alt="" /> </div>
                        <div class="full_width hotel_list_sorting">
                            @foreach($data_wisata as $wisata)
                            <div class="sorting_places_wrap  list_sorting_view">
                                <div class="col-lg-5 col-md-5 col-sm-5 padding_none">
                                    <div class="thumb_wrape">
                                        <a href="#">
                                            @if($wisata->wisata_foto)
                                                <img src="{{ asset($wisata->wisata_foto)}}" width="330" height="244" alt="hotel thumb">
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
                                    <div class="flight_grid_desc flight_list_desc">
                                        <div class="bottom_title">
                                            <h5><a href="#">{{$wisata->wisata_nama }}</a></h5>
                                            <span class="flight_details">{{ucwords(strtolower($wisata->wisata_nama))}}</span>
                                        </div>

                                        <div class="flight_details_schedule">
                                            <div class="take_off_landing">
                                                <h5>Jam Buka</h5>
                                                <p>{{$wisata->jam_buka}}</p>
                                            </div>
                                            <div class="take_off_landing">
                                                <h5>Jam Tutup</h5>
                                                <p>{{$wisata->jam_tutup}}</p>
                                                </div>
                                            </div>

                                        <div class="total_time_schedule">{{$wisata->wisata_alamat}}</div>

                                               <div class="book_now_btn pull-right"><br>
                                                <button onclick="show_modal_page('{{$wisata->wisata_id}}')" class="btn_green proceed_buttton btns">Cek Kuota</button>&nbsp;
                                                <button onclick="show_modal('{{$wisata->wisata_id}}')" class="btn_green proceed_buttton btns">Pesan Sekarang</button>&nbsp;

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                            <div id="wisata_html_det">

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
