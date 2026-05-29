
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
        <form class="form-horizontal form-label-left" id="formEntry" method="post">    
            @csrf          
            <div class="mb-3">
                <label class="form-label"><b>Tanggal : </b></label>
                <input type="text" class="{{$data['classFormControl']}} datepicker" id="tgl_cari" value="" name="tgl_cari">            
            </div>            
            <div class="toolbar hidden-print">
                <div class="text-start">
                    <button type="button" id="btn_search" class="btn btn-primary btn-sm"><i class="bx bxs-search"></i> Cari Data</button>                
                </div>                
            </div>
            <!--<div class="form_custom1" id="show_table"></div>--> 
        </form>      
    </div>
</div>

<div class="card border-top border-0 border-4 border-success" id="contentJB">
    <div class="card-body" >
        <div class="toolbar hidden-print">
            <div class="text-end">
                <button type="button" id="btn_back" class="btn btn-primary btn-sm"><i class="bx bxs-arrow-from-right"></i> Kembali</button>
                <button type="button" id="btn_print" class="btn btn-dark btn-sm"><i class="bx bxs-printer"></i> Print</button>                
            </div>
            <hr/>
        </div>
        
        <div id="myModalHorizontalprint" style="margin:10px">
            <div class="row">
                <div class="d-flex align-items-center">  
                    <div class="col-sm-1">                                    
                        <img src="{{ URL::asset(session("logoHeaderTransaksi")) }}" alt="" />                
                    </div>                    
                    <div class="col-sm-11" style="margin-left:30px">
                        <h4 class="name"><a href="javascript:;"><strong>{{$data['title']}}</strong></a></h4>
                        <h6 class="name font-16"><a href="javascript:;">{{$data['subtitle']}}</a></h6>
                        <h6 class="name font-14"><span id="per_tanggal"></span></h6>
                    </div>                                                                            
                </div>
            </div>            
            <hr style="border:solid 2px #0d6efd;"/>            <center><div id="loading"><img src="{{asset('bank_stiep/images/load.gif')}}" height='100'></div></center><br>            
            <div class="form_custom1" id="show_table"></div>
       </div>
    </div>
</div>

<script src="{{ asset('additional/js/lap_nasabah_pinjaman_md.js?v=1.00') }}"></script>
