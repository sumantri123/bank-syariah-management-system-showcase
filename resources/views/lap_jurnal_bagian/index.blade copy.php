@extends('frontend.layout_home.default')

@section('content')

<div class="card">
    <div class="card-body" >
        <div class="toolbar hidden-print">
            <div class="text-end">
                <button type="button" id="btn_print" class="btn btn-dark"><i class="bx bxs-printer"></i> Print</button>                
            </div>
            <hr/>
        </div>
        
        <div id="myModalHorizontalprint">
            <div class="row">
                <div class="col-sm-8">
                    <h4 class="card-title">{{$data['subtitle']}}</h4>                
                </div>
                <div class="col-sm-4">
                    <h4 class="card-title">{{$data['title']}}</h4>
                    <h5 class="card-title">Per Tanggal : {{date('d-F-Y')}}</h5>
                </div>                                
            </div>
            <hr/>
            <div class="form_custom1" id="show_table"></div>
       </div>
    </div>
</div>

@endsection    

@push('scripts')
    <script src="{{ asset('additional/js/lap_jurnal_bagian.js') }}"></script>

@endpush