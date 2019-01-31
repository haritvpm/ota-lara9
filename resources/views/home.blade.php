@extends('layouts.app')

@section('content')

@if(!auth()->user()->isAudit() && !auth()->user()->isServices())

<style type="text/css">
  

.info-box {
    min-height: 45px;
    small {
        font-size: 8px;
    }
        margin-bottom: 8px;
  
}



.info-box-icon {

        height: 45px;
        width: 45px;
        font-size: 32px;
        line-height: 45px;        
    }
.info-box-content {
    margin-left: 45px;
}
.info-box-text {
    
    font-size: 12px;
}

.info-box-number {
   
    font-size: 12px;
}
</style>

<div id="app" class="text-right">
       <!--  <i class="fa fa-phone-square"></i> 2422 &nbsp;&nbsp; -->
       
        {{date("l, F j (d-m-y)")}} 
  </div>

  <p>
    Welcome !<br> For help, call <i class="fa fa-phone-square"></i> 2422 &nbsp;&nbsp; {!!$welcometext!!}
    @if(auth()->user()->isAdmin())
    <i>Loaded in {{$timetaken}}
    @endif
  </p>


   <div class="box box-warning">
    <div class="box-header with-border">
      <div class="box-title">Session: <strong>{{  implode(",",$session_array) }} </strong>
      </div>
     <div class="box-body">
                  
        <div class="row">
         <div class="col-md-6">

          @if( env('SHOW_LEGSECTT', true))
          @if(auth()->user()->isAdmin() || !auth()->user()->isOD())
          Total Forms : {{ $total }} <br>
          @endif
          @endif


          @if(auth()->user()->isAdmin() || auth()->user()->isOD())
          Total Forms (Other Dept): {{ $total_other }} 
          @endif

         </div>
       <!--  <div class="col-md-4">
          @if(auth()->user()->isAdmin())
          @if( env('SHOW_LEGSECTT', true))
          <table class='table .table-condensed   table-striped' style="border-top: none;">
          <tr>
            <td >Amount for {{$session_latest}}</td>
            <td>Submitted</td>
            <td>Total</td>
              
          </tr>
          <tr>
             <td >Sectt (approx)</td>
             <td>{{$amount_approved_sectt }}</td>
             <td>{{$amount_all_sectt }}</td>
           
          </tr>
          <tr>
             <td >Sectt + Hostel</td>
             <td>{{ $amount_approved }}</td>
             <td>{{ $amount_all }}</td>
          </tr>
        </table>
         @endif
         @endif
    
      </div> -->
      </div>

     </div>
         
    </div>
  </div>



@if( env('SHOW_LEGSECTT', true))
@if(auth()->user()->isAdmin() || !auth()->user()->isOD())
 <div class="row">
    <!-- <div class="col-md-2">
         <a href="<?=URL::to('admin/my_forms')?>">
         <div class="info-box">
                    
          <span class="info-box-icon bg-yellow"><i class="fa fa-star-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total</span>
            <span class="info-box-number">{{ $total }}</span>
          </div>
         
        </div>
        </a>
    </div> -->
    <div class="col-md-3">
         <!-- a href="<?=URL::to('admin/my_forms?status=Draft')?>"-->
         <a href="<?=URL::to('admin/my_forms')?>">
         <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-gray"><i class="fa fa-edit"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Drafts</span>
            <span class="info-box-number">{{ $draft }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
         </a>
    </div>

    @if($to_approve != -1)
    <div class="col-md-3">
          <!-- <a href="<?=URL::to('admin/my_forms?status=To_approve')?>"> -->
          <a href="<?=URL::to('admin/my_forms')?>">
         <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-red"><i class="fa fa-eye"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">To Approve</span>
            <span class="info-box-number">{{ $to_approve }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
         </a>
    </div>
    @endif

    @if($pending_approval != -1)
    <div class="col-md-3">
         <a href="<?=URL::to('admin/my_forms?status=Pending')?>">
         <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-blue"><i class="fa fa-mail-forward"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Sent</span>
            <span class="info-box-number">{{ $pending }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
         </a>
    </div>
     @endif

    <div class="col-md-3">
         <a href="<?=URL::to('admin/my_forms?status=Submitted')?>">
         <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-green"><i class="fa fa-thumbs-up"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Submitted</span>
            <span class="info-box-number">{{ $submitted }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
         </a>
    </div>

    @if(auth()->user()->isAdmin())
    <div class="col-md-3">
                    
         <div class="info-box">
        <div class="info-box">
          <span class="info-box-icon bg-red"><i class="fa fa-inr "></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total for {{$session_latest}}</span>
            <span class="info-box-number"> Sect <span>&#8776; </span>{{$amount_all_sectt }},  All = {{ $amount_all }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
       </div>
    </div>
    @endif

 </div>



 <!-- Exemption status -->
  @can('myexemptionform_access')
    @if($to_approve_ex > 0 || $draft_ex > 0)

    <a href="<?=URL::to('admin/myexemptionforms')?>">
    <div class="alert alert-danger" role="alert">
      
      @if($to_approve_ex > 0)
      <strong>{{$to_approve_ex}} exemption form(s)</strong> to approve.&nbsp;
      @endif

      @if( $draft_ex > 0)
      <strong>{{$draft_ex}} exemption form(s)</strong> still in draft stage.
      @endif

    </div>
    </a>
    @endif
  @endcan

 @endif
 @endif


<!-- other dept -->

@if(auth()->user()->isAdmin() || auth()->user()->isOD())
 <div class="row">

   <!--  <div class="col-md-2">
         <a href="<?=URL::to('admin/my_forms_others')?>">
         <div class="info-box">
                   
          <span class="info-box-icon bg-yellow"><i class="fa fa-star-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total (OD)</span>
            <span class="info-box-number">{{ $total_other }}</span>
          </div>
         
        </div>
        </a>
    </div> -->
    <div class="col-md-3">
         <a href="<?=URL::to('admin/my_forms_others')?>">
         <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-gray"><i class="fa fa-edit"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Drafts (OD)</span>
            <span class="info-box-number">{{ $draft_other }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
         </a>
    </div>

    @if($to_approve_other != -1)
    <div class="col-md-3">
          <a href="<?=URL::to('admin/my_forms_others')?>">
         <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-red"><i class="fa fa-eye"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">To Approve by Me (OD)</span>
            <span class="info-box-number">{{ $to_approve_other }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
         </a>
    </div>
    @endif


    <div class="col-md-3">
         <a href="<?=URL::to('admin/my_forms_others?status=Pending')?>">
         <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-blue"><i class="fa fa-mail-forward"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Sent (OD)</span>
            <span class="info-box-number">{{ $pending_other }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
         </a>
    </div>
    <div class="col-md-3">
         <a href="<?=URL::to('admin/my_forms_others?status=Submitted')?>">
         <div class="info-box">
          <!-- Apply any bg-* class to to the icon to color it -->
          <span class="info-box-icon bg-green"><i class="fa fa-thumbs-up"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Submitted (OD)</span>
            <span class="info-box-number">{{ $submitted_other }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
         </a>
    </div>
    @if(auth()->user()->isAdmin())
    <div class="col-md-3">
                    
         <div class="info-box">
        
          <span class="info-box-icon bg-green"><i class="fa fa-inr "></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Aproved for {{$session_latest}}</span>
            <span class="info-box-number">Sect <span>&#8776;</span> {{ $amount_approved_sectt }}, All = {{ $amount_approved}}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
       
    </div>
    @endif

 </div>
 @endif

@endif <!-- not audit -->


@if( env('SHOW_LEGSECTT', true))
@if(!auth()->user()->isAdmin() && !auth()->user()->isOD() && !auth()->user()->isServices() )
   <p>
  View <a href="<?=URL::to('admin/goview/go.pdf')?>"  target="_blank" > GO(Rt) 123/2016/Leg dtd 20/1/2016</a>  regarding overtime allowance
  </p>
   

  @if(!auth()->user()->isAudit())

   <div >
    @if( $marqueetext != '' && $marqueetext != '-')
  <MARQUEE style="color:blue;" BEHAVIOR=slide SCROLLDELAY=50 WIDTH=70%> {{$marqueetext}} </MARQUEE>
  @endif
    </div>


    <div >Click <strong><a href="<?=URL::to('admin/my_forms')?>">My Forms</strong></a> to prepare or approve OTA Claim forms
      </div>
    
  @endif
@endif
@endif



 @if(auth()->user()->isAdmin())

  @if( env('SHOW_LEGSECTT', true))
  <div class="box box-primary">
  <div class="box-header with-border">
  <div class="box-title">Form owner pending status</div>
  <div class="box-body">

     <div class="tabpanel">
       <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
        @foreach($info as $session => $sessinfo)
          @php
          $idwithnodot = str_replace(".","",$session);
          @endphp
           <li role="presentation" @if($loop->last) class="active" @endif>
              <a href="#tab-{{$idwithnodot}}" aria-controls="#tab-{{$idwithnodot}}" role="tab" data-toggle="tab">{{ $session }}</a>
           </li>

        @endforeach
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <br>
            @foreach($info as $session => $sessinfo)
             @php
             $idwithnodot = str_replace(".","",$session);
             $totalpending = 0;
             @endphp
             <div  role="tabpanel"   @if($loop->last) class="tab-pane active" @else class="tab-pane" @endif id="tab-{{$idwithnodot}}">
                               
             
             <ol class="row">
            
             @foreach($sessinfo as $k => $v)
               @if($v['created'] == 0 )   @continue;   @endif
              <li class="col-sm-6">
                
                {{ $k }}  : <strong><span class="text-warning">{{$v['created']}}</span></strong>
                @if(!$loop->last), @endif
                </li>
                @php
                $totalpending += $v['created'];
                @endphp
             @endforeach
            
             </ol> 
              
             <p>Total: {{$totalpending}}  </p>

            </div>
            @endforeach
       </div>
     </div>
 </div> 
 </div>
 </div>

  <!-- <div class="box box-primary">
  <div class="box-header with-border">
  <div class="box-title">Form created status. ('Pending' includes forwarded forms)</div>

  <div class="box-body">
    <div class="tabpanel">
       <!- - Nav tabs - ->
        <ul class="nav nav-tabs" role="tablist">
        @foreach($info_submitted as $session => $sessinfo)
          @php
          $idwithnodot = str_replace(".","",$session);
          @endphp
           <li role="presentation" @if($loop->last) class="active" @endif>
              <a href="#tab2-{{$idwithnodot}}" aria-controls="#tab2-{{$idwithnodot}}" role="tab" data-toggle="tab">{{ $session }}</a>
           </li>

        @endforeach
        </ul>

        <!- - Tab panes - ->
        <div class="tab-content">
        <br>
        @foreach($info_submitted as $session => $sessinfo)
         @php
         $idwithnodot = str_replace(".","",$session);
         @endphp
         <div  role="tabpanel"   @if($loop->last) class="tab-pane active" @else class="tab-pane" @endif id="tab2-{{$idwithnodot}}">
                

        <ol class="row">
         @foreach($sessinfo as $k => $v)
                        
            <li class="col-sm-6 small">
            {{ $k }} :  Pending-<span class="text-primary">{{$v['created']}}</span>, Submitted- <span class="text-success">@if($v['submitted'] > 0) {{$v['submitted']}} @else {{$v['submitted']}}@endif </span>

            <!- - @if(!$loop->last), @endif - ->
              </li>
        @endforeach
         </ol>
        </div> 
        @endforeach
        </div>
    </div> 
  </div> 
  </div>
  </div>
 -->

 
    <div class="box box-success">
    <div class="box-header with-border">
    <div class="box-title">Submitted forms per session (incl PA2MLA)</div>
     <div class="box-body">
      @foreach($session_array as $session)
      
      {{$session}} ->  Total :  <strong>{{$formcount[$session] }}</strong>
      @if( $formcount[$session] )
      Last submitted by : <strong>{{$formlastsubmittedby[$session]}}</strong> on <strong>{{$formlastsubmitteddate[$session]}}</strong>
       Form no: {{$last_form_no[$session]}}
      @endif
      <br>
      @endforeach
    </div> 
    </div> 
    </div>
  

    @if(count($users_not_submitted_yet))
    <div class="box box-warning">
    <div class="box-header with-border">
    <div class="box-title">Users who have not created any forms yet</div>
      <div class="box-body">
      @foreach($users_not_submitted_yet as $k => $v)
          {{ $k }} <span class="text-danger"> {{$v}} </span>
           @if(!$loop->last), @endif
      @endforeach
      </div>
    </div>
    </div> 
    @endif

    <hr>
    @endif <!-- if( env('SHOW_LEGSECTT')) -->

    <div class="box box-success">
    <div class="box-header with-border">
    <div class="box-title">Submitted forms per session (Other Dept)</div>
     <div class="box-body">
      @foreach($session_array as $session)
      
      {{$session}} ->  Total :  <strong>{{$formcountother[$session] }}</strong>
      @if( $formcountother[$session] )
      Last submitted by : <strong>{{$formotherlastsubmittedby[$session]}}</strong> on <strong>{{$formotherlastsubmitteddate[$session]}}</strong>
      
      @endif
      <br>
      @endforeach
    </div> 
    </div> 
    </div>


  @endif

  <!-- @if(auth()->user()->isAdmin()||auth()->user()->isServices())

  <div class="box box-primary">
  <div class="box-header with-border">
  <div class="box-title">Exemption Form pending status</div>
  <div class="box-body">

     <div class="tabpanel">
       <!- - Nav tabs - ->
        <ul class="nav nav-tabs" role="tablist">
        @foreach($info_ex as $session => $sessinfo)
          @php
          $idwithnodot = str_replace(".","",$session);
          @endphp
           <li role="presentation" @if($loop->first) class="active" @endif>
              <a href="#tab-{{$idwithnodot}}" aria-controls="#tab-{{$idwithnodot}}" role="tab" data-toggle="tab">{{ $session }}</a>
           </li>

        @endforeach
        </ul>
        <!- - Tab panes - ->
        <div class="tab-content">
            <br>
            @foreach($info_ex as $session => $sessinfo)
             @php
             $idwithnodot = str_replace(".","",$session);
             $totalpending = 0;
             @endphp
             <div  role="tabpanel"   @if($loop->first) class="tab-pane active" @else class="tab-pane" @endif id="tab-{{$idwithnodot}}">
                               
             
             <ol class="row">
            
             @foreach($sessinfo as $k => $v)
               @if($v['created'] == 0 )   @continue;   @endif
              <li class="col-sm-6">
                
                {{ $k }}  : <strong><span class="text-warning">{{$v['created']}}</span></strong>
                @if(!$loop->last), @endif
                </li>
                @php
                $totalpending += $v['created'];
                @endphp
             @endforeach
            
             </ol> 
              
             <p>Total: {{$totalpending}}  </p>

            </div>
            @endforeach
       </div>
     </div>
 </div> 
 </div>
 </div>

 @endif
 -->

@stop


<script type="text/javascript" src="{{ URL::asset('js/vue-sweetalert.js') }}"></script>

@section('javascript') 
@parent


<script type="text/javascript">
 Vue.use(VueSweetAlert.default)
 var urlprofile = "{{url('change_displayname')}}"

</script>

<script type="text/javascript" src="{{ URL::asset('js/home.1.js') }}"></script>


@endsection
