
<div class="card border-top border-0 border-4 border-success" id="headerJB">
    <div class="card-body" >                
        <div class="row">
            <div class="d-flex align-items-center">  
				<div class="col-sm-1" align="center">                                    
					<img src="{{ URL::asset(session("logoHeaderTransaksi")) }}" alt="" />                
				</div>
				<div class="col-sm-11" style="margin-left:30px">
					<h4 class="name"><a href="javascript:;"><strong>{{$data['title']}}</strong></a></h4>
					<h6 class="name font-16"><a href="javascript:;">{{$data['subtitle']}}</a></h6>
				</div>                                                                           
			</div>
        </div>
        <hr/>            
        <div class="mb-3">
            <label class="form-label"><b>Tanggal Monitoring Rek Perantara: </b></label>
            <input type="text" class="{{$data['classFormControl']}} datepicker" id="tgl_cari" value="" name="tgl_cari">            
        </div>            
        <div class="toolbar hidden-print">
            <div class="text-start">
                <button type="button" id="btn_search" class="{{$data['btnClass']}}"><i class="bx bxs-search"></i> Cari Data</button>                
            </div>                
        </div>
        <!--<div class="form_custom1" id="show_table"></div>-->       
    </div>
</div>

<div class="card border-top border-0 border-4 border-success" id="contentJB">
    <div class="card-body" >
        <div class="toolbar hidden-print">
            <div class="text-end">
                <button type="button" id="btn_back" class="{{$data['btnClass']}}"><i class="bx bxs-arrow-from-right"></i> Kembali</button>
            </div>
            <hr/>
        </div>
        
        <div id="myModalHorizontalprint">
            <div class="row">
                <div class="d-flex align-items-center">  
					<div class="col-sm-1" align="center">                                    
						<img src="{{ URL::asset(session("logoHeaderTransaksi")) }}" alt="" />                
					</div>
					<div class="col-sm-11" style="margin-left:30px">
						<h4 class="name"><a href="javascript:;"><strong>{{$data['title']}}</strong></a></h4>
						<h6 class="name font-16"><a href="javascript:;">{{$data['subtitle']}}</a></h6>
						<h6 class="name font-16"><span id="per_tanggal"></span></h6>
					</div>                                                                            
				</div>                                  
            </div>
            <hr/>
            <div class="form_custom1" id="show_table"></div>
       </div>
    </div>
</div>

<script src="{{ asset('additional/js/mon_rek_perantara.js') }}"></script>
