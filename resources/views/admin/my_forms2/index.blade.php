@extends('layouts.app')


@section('content')

       
    <div  id="app">

    @if(!auth()->user()->isAdminorAudit()) 

    <h4 class="page-title">Create Forms</h4>

    <p>
        <a href="{{ route('admin.my_forms2.create') }}" class="btn btn-success"  data-toggle="tooltip" title="Form for 1st, 2nd or 3rd OT. Form to be submitted on the next working day itself."  >@lang('quickadmin.qa_new_daily_form')</a>
        <a href="{{ route('admin.my_forms2.create_sitting') }}" class="btn btn-warning" data-toggle="tooltip" title="Form for total sitting days attended. Form to be submitted only after an assembly session is over."  > New Sitting-days Form</a>
    </p>
   
    @endif

    <hr>

    @if(!auth()->user()->isAudit()) 
    <p>
        
        <ul class="nav nav-pills ">
        @if(!auth()->user()->isAdmin())
        <li class="nav-item" @click="setActive('todo')" ><a class="nav-link" :class="{ active: isActive('todo') }" href="#">ToDo</a></li>
        @else
        <li  class="nav-item" @click="setActive('all')" ><a class="nav-link"  :class="{ active: isActive('all') }" href="#">All</a></li>
        
        <li class="nav-item"  @click="setActive('Draft')" ><a class="nav-link" :class="{ active: isActive('Draft') }" href="#">Draft</a></li>
        @endif
         <!-- @if($to_approve != -1) -->
        <!-- <li @click="setActive('To_approve')" :class="{ active: isActive('To_approve') }"><a href="#">To Approve</a></li> -->
        <!-- @endif -->
        
        
        @if($pending_approval != -1)
        <li  class="nav-item" @click="setActive('Pending')" ><a class="nav-link" :class="{ active: isActive('Pending') }"  href="#">Sent for Approval</a></li>
        @endif
       
        <li  class="nav-item" @click="setActive('Submitted')" ><a class="nav-link" :class="{ active: isActive('Submitted') }" href="#">Submitted to Accounts</a></li>

      <!--   @if(auth()->user()->isAdmin()) 
        <li @click="setActive('')" :class="{ active: isActive('') }"><a href="#">All</a></li>
        @endif -->

        </ul>
    </p>
    @endif

    <div class="">
  
    <div class="table-responsive">
        <table class="table table-borderless table-striped table-sm}}">
            <thead>
                <tr>
                    @if(auth()->user()->isAdminorAudit()) 
                    <th><a href="<?=URL::to('admin/my_forms2?sort=id'.$querystr)?>">ID</a></th>
                    @endif

                    @if(auth()->user()->isAdmin()) 
                    <th>F.No</th>
                    @endif

                    <th><a href="<?=URL::to('admin/my_forms2?sort=session'.$querystr)?>">Session</a></th>
                    <th><a href="<?=URL::to('admin/my_forms2?sort=creator'.$querystr)?>">Created by</a></th>
                    <th><a href="<?=URL::to('admin/my_forms?sort=overtime_slot'.$querystr)?>">OT</a></th>
                    <th><a href="<?=URL::to('admin/my_forms2?sort=duty_date'.$querystr)?>">Duty Date</a></th>
                    <th><a href="<?=URL::to('admin/my_forms2?sort=owner'.$querystr)?>">Status</a></th>

                    @if(auth()->user()->isAdmin())
                    <th>Submtd</th>
                   <!--  <th>Updated</th> -->
                    @endif

                  <!--   <th>Remarks</th> -->
                                        

                    <th>&nbsp;</th>

                </tr>
            </thead>
            
            <tbody>
                @if ($forms->total() > 0)
                    @foreach ($forms as $form)
                        <tr data-entry-id="{{ $form->id }}">
                           
                            @if(auth()->user()->isAdminorAudit()) 
                            <td ><small> {{ $form->id }}</small> </td>
                            @endif
                            @if(auth()->user()->isAdmin()) 
                            <td >{{ $form->form_no }}</td>
                            @endif
                            
                            <td >{{ $form->session }}</td>
                            <td >
                            @if( $form->creator == Auth::user()->username )
                                Me
                            @else
                                @if(optional($form->created_by)->Title != null)
                                   {{ optional($form->created_by)->Title }} <small>({{$form->creator}})</small>
                                @else
                                    <small>{{$form->creator}}</small>
                                @endif
                            @endif
                            </td>
                            <td > 
                            @if( $form->overtime_slot == 'Multi')
                                1<sup>st</sup>/
                           
                                2<sup>nd</sup>/
                            
                                3<sup>rd</sup>
                           
                            @else
                                Sitting 
                            @endif

                            </td>
                            <td>
                            @if($form->overtime_slot == 'Sittings')
                            
                               <!--  @php
                                $session_no = substr($form->session,strpos($form->session,'.')+1);
                                @endphp -->
                                <!-- <small>Session:</small> {{ $session_no }} -->
                                <!-- {{ $form->date_from }} to {{ $form->date_to }} -->      
                            
                            @else
                            
                                {{ $form->duty_date }}
                                @php
                                $daytype = $form->day_type() ;   
                                @endphp
                                @if($daytype == 'S')
                                <small><i class="fa fa-university" style="color:green"></i></small>
                                @elseif($daytype == 'H')
                                <small><i class="fa fa-calendar-o" style="color:red"></i></small>
                                @else
                                <small><i class="fa fa-calendar-o" style="color:black"></i></small>
                                @endif    
                                
                                

                            
                            @endif
                            </td>

                                                       
                            <td>
                               
                            @if($form->owner == Auth::user()->username)
                                @if($form->owner == 'admin')
                                 <span class="text-default"><i class="fa fa-thumbs-up" style="color:green"></i> @admin</span>
                                @else
                                    @if($form->owner != $form->creator)
                                    <span class="text-default"><i class="fa fa-eye" style="color:red"></i> To approve</span> 
                                    @else
                                    <span class="text-default"><i class="fa fa-edit"></i> Draft</span>
                                    @endif    
                                @endif
                            @else
                                @if($form->owner == 'admin')
                                <span class="text-default"><i class="fa fa-thumbs-up" style="color:green"></i> <small>Submitted to Accounts</small></span> 
                                @else
                                    @if($form->owner == $form->creator)
                                     <span class="text-default"><i class="fa fa-edit"></i> Draft</span>
                                    @else
<i class="fa fa-mail-forward" style="color:blue"></i> at <span class="text-default">{{ optional($form->owned_by)->Title  ?? $form->owner }} {{ optional($form->owned_by)->displayname}}</span>
                                    @endif                  
                                @endif                                
                            @endif
                            
                            @if($form->form_no < 0)
                            <small><span style="color:red"> (Withheld)</span></small>
                            @endif

                             </td>

                            @if(auth()->user()->isAdmin())
                            <td ><small>
                            @if($form->owner == 'admin')
                            {{ date('d-m-y', strtotime($form->submitted_on)) }}
                            @endif
                            </small></td>
                           <!--  <td ><small>
                           {{$form->updated_at->timezone('Asia/Kolkata')->format('d-m-y H:i')}}
                            </small></td> -->
                            @endif

                          
                            <td class="text-nowrap">
                                <a href="{{ route('admin.my_forms2.show',[$form->id]) }}" class="btn btn-dark">@lang('quickadmin.qa_view') </a>  <small>{{$form->overtimes()->count()}} </small>
                                
                              
                               
                            </td>

                        </tr>
                    @endforeach
                    
                @else
                    <tr>
                        <td colspan="11">@lang('quickadmin.qa_no_entries_in_table')</td>
                    </tr>
                @endif

                              

            </tbody>
        </table>

        {{ $forms->onEachSide(5)->links() }}


        @if ($forms->total() > 0)
        <div><small>Showing {{($forms->currentpage()-1)*$forms->perpage()+1}} to {{(($forms->currentpage()-1)*$forms->perpage())+$forms->count()}}
        of  {{$forms->total()}} forms
        </small></div>          
         @endif

        </div>
    </div>
  
    
<button v-if="!show_search_form"  type="button" v-on:click
="show_search_form = !show_search_form" class="btn btn-default">Search &raquo;</button>
<div  v-if="show_search_form">
    <form action="" method="get" id="filter" class="form-inline" >

        <div class="form-group">
         <select class="form-control" name="session">
                
                @foreach($session_array as $session)
                @if($session == \Request('session'))
                   <option selected>{{$session}}</option>
                @else
                    <option>{{$session}}</option>
                @endif
                @endforeach
                        
        </select>
        </div>
        @if(Auth::user()->isAdminorAudit())
        <div class="form-group">
         <input  class="form-control" placeholder="Form No." type="text" name = "idfilter" value="{{ \Request('idfilter')  }}" rel="filter">
        </div>

        
        <div class="form-group">                                
        Created By <select class="form-control" name="created_by">
        
                <option value="all">Any</option>

                @foreach($added_bies as $val => $added_by)
                @if($val == \Request('created_by'))
                <option value="{{$val}}" selected>{{$added_by}} ({{$val}})</option>
                @else
                     <option value="{{$val}}">{{$added_by}} ({{$val}})</option>
                @endif
                @endforeach
                              
        </select>
        </div>
        @endif

        <div class="form-group">                                
        OT <select class="form-control" name="overtime_slot">
                <option value="">All</option>
                 <option value="Sittings"  {{ \Request('overtime_slot') == 'Sittings' ? 'selected' : '' }}>Sittings</option>

                 <option value="Non-Sittings"  {{ \Request('overtime_slot') == 'Non-Sittings' ? 'selected' : '' }}>Non-Sittings</option>
                 <option value="Withheld"  {{ \Request('overtime_slot') == 'Withheld' ? 'selected' : '' }}>Withheld</option>
                
        
        </select>
        </div>
        Date <input  class="form-control" placeholder="dd-mm-yyyy|S|W|H|NS" type="text" name = "datefilter" value="{{ \Request('datefilter')  }}" rel="filter">
        <input  class="form-control" placeholder="Name/Pen" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter">
        <input  class="form-control" placeholder="Designation" type="text" name = "desigfilter" value="{{ \Request('desigfilter')  }}" rel="filter">

        <!-- <input  type="hidden" name = "status" value="{{  \Request('status')   }}" rel="filter"> -->

        <input  type="hidden" name = "status" value="all" rel="filter">

        @if(Auth::user()->isAdmin())
        
        <input  class="form-control" placeholder="submittedby" type="text" name = "submittedbyfilter" value="{{\Request('submittedbyfilter')}}" rel="filter">

        @endif
       
        <a href="{{route('admin.my_forms2.index')}}" class="btn btn-default">Reset</a>

        <button type="submit" class="btn btn-default" rel="filter">Search</button>
        
    </form>
     </div>

    <br>
    <!-- view all -->

    @if(Auth::user()->isAdminorAudit())
     @endif
  
    </div>

@stop


@section('javascript') 

<script type="text/javascript">
    
    function showform(n){
    // $("#form_search").show();
    //var form = document.getElementById(n);
    //if (form !== null)
    {    
        document.getElementById(n).style.display = "inline-block";
        document.getElementById('form_search_button').style.display = "none";
    }
   // else{
       // document.getElementById("form_search").style.display = "none";
   // }
       
}


</script>

<script>

function getSearchParameters() {
      var prmstr = window.location.search.substr(1);
      return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
}

function transformToAssocArray( prmstr ) {
    var params = {};
    var prmarr = prmstr.split("&");
    for ( var i = 0; i < prmarr.length; i++) {
        var tmparr = prmarr[i].split("=");
        params[tmparr[0]] = tmparr[1];
    }
    return params;
}

function replaceUrlParam(url, paramName, paramValue){
    if(paramValue == null)
        paramValue = '';
    var pattern = new RegExp('\\b('+paramName+'=).*?(&|$)')
    if(url.search(pattern)>=0){
        return url.replace(pattern,'$1' + paramValue + '$2');
    }
    return url + (url.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue 
}


var params = getSearchParameters();




var vm = new Vue({
  el: '#app',
  data: {
        activeItem: params.status == undefined ? 'todo' : params.status,
        show_search_form : false,
    },

    mounted: function () {
         //if there are any search params, dont hide our form
         //we cannot check for empty params, as there are status and page params that can be non-empty even on not searching
        if( params.session != undefined || params.overtime_slot != undefined || params.datefilter!= undefined || params.namefilter != undefined || adminoraudit)
        {
            this.show_search_form = true
        }
        
    }, 
      // define methods under the `methods` object
  methods: {
   
    isActive: function (menuItem) {
      return this.activeItem === menuItem
    },
    setActive: function (menuItem) {
      
      var str =  replaceUrlParam(window.location.href, 'status', menuItem)
      //current page might be higher than pages in new location
      str =  replaceUrlParam(str, 'page', 1)

      //this.activeItem = menuItem // no need for Vue.set()
      window.location.href = str
     

    }
   
  }
})

</script>

@stop