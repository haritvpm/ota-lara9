@extends('layouts.app')


@section('content')

<style>

.nav>li>a {
    padding-top: 3px;
    padding-bottom: 3px;
}
</style>

    <h3 class="page-title">Forms <small>(Other Dept)</small></h3> 


    
    <div  id="app">

    @if(!auth()->user()->isAdmin()) 
    <p>
        <a href="{{ route('admin.my_forms_others.create') }}" class="btn btn-success"><i class="fa fa-file-text-o"></i> @lang('quickadmin.qa_new_daily_form')</a>
        <a href="{{ route('admin.my_forms_others.create_sitting') }}" class="btn btn-warning"><i class="fa fa-file-text-o"></i> New Sitting-days Form</a>
    </p>
   
    @endif

    <hr>

    <p>
    
        
       <ul class="nav nav-pills ">
        <!-- <li @click="setActive('all')" :class="{ active: isActive('all') }"><a href="#">All</a></li> -->
        <li @click="setActive('Draft')" :class="{ active: isActive('Draft') }"><a href="#">Draft</a></li>
        <!-- <li @click="setActive('Pending')" :class="{ active: isActive('Pending') }"><a href="#">Pending Approval</a></li> -->
        @if($to_approve != -1)
        <li @click="setActive('To_approve')" :class="{ active: isActive('To_approve') }"><a href="#">To Approve</a></li>
        @endif
        <li @click="setActive('Submitted')" :class="{ active: isActive('Submitted') }"><a href="#">Submitted to Accounts</a></li>
        </ul>
    </p>

    <div class="panel panel-default">
    <div class="panel-heading">
        @lang('quickadmin.qa_list')
    </div>

    <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped table-condensed }}">
            <thead>
                <tr>
                  
                    <th><a href="<?=URL::to('admin/my_forms_others?sort=id'.$querystr)?>">ID</a></th>
                    <th><a href="<?=URL::to('admin/my_forms_others?sort=session'.$querystr)?>">Session</a></th>
                    <th><a href="<?=URL::to('admin/my_forms_others?sort=creator'.$querystr)?>">Created by</a></th>
                    <th><a href="<?=URL::to('admin/my_forms_others?sort=overtime_slot'.$querystr)?>">OT</a></th>
                    <th><a href="<?=URL::to('admin/my_forms_others?sort=duty_date'.$querystr)?>">Duty Date</a></th>
                    <th><a href="<?=URL::to('admin/my_forms_others?sort=owner'.$querystr)?>">Status</a></th>     
                    <!-- <th>Remarks</th> -->
                    <!-- <th>Last Updated</th> -->
                    <th>&nbsp;</th>

                </tr>
            </thead>
            
            <tbody>
                @if (count($forms) > 0)
                    @foreach ($forms as $form)
                        <tr data-entry-id="{{ $form->id }}">
                           

                            <td >{{ $form->id }}</td>
                            <td >{{ $form->session }}</td>
                            <td >
                            @if( $form->creator == Auth::user()->username )
                                Me
                            @else
                                {{ $form->created_by->name }}
                            @endif
                            </td>
                            <td > 
                            @if( $form->overtime_slot == 'First')
                                1<sup>st</sup>
                            @elseif( $form->overtime_slot == 'Second')
                                2<sup>nd</sup>
                            @elseif( $form->overtime_slot == 'Third')
                                3<sup>rd</sup>
                            @else
                                Sitting
                            @endif

                            </td>
                            <td>
                            @if($form->overtime_slot == 'Sittings')
                            
                                {{ $form->date_from }} to {{ $form->date_to }}      
                            
                            @else
                            
                                {{ $form->duty_date }}
                            
                            @endif
                            </td>

                                                       
                            <td>
                            @if($form->owner == Auth::user()->username)
                                @if($form->owner == 'admin')
                                <span class="label label-success">With Admin</span> 
                                @else
                                    @if($form->owner != $form->creator)
                                    <span class="text-default"><i class="fa fa-eye" style="color:red"></i> To approve</span> 
                                    @else
                                    <span class="text-default"><i class="fa fa-edit"></i> Draft</span>
                                    @endif    
                                @endif
                            @else
                                @if($form->owner == 'admin')
                                 <span class="text-default"><i class="fa fa-thumbs-up" style="color:green"></i> Submitted to Accounts</span>  
                                @else
                                    @if($form->owner == $form->creator)
                                     <span class="text-default">Draft</span>
                                    @else
                                    <i class="fa fa-mail-forward" style="color:blue"></i> Now at <span class="text-default">{{ $form->owned_by->name }} {{ $form->owned_by->displayname }}</span> 
                                    @endif                  
                                @endif                                
                            @endif

                             </td>
                            
                            <!-- <td >{{ str_limit($form->remarks, 30)  }}</td> -->

                            <!-- <td>{{$form->updated_at}}</td> -->

                            <td>
                                <a href="{{ route('admin.my_forms_others.show',[$form->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a> <small>({{$form->overtimes()->count()}}) </small>
                                                                
                                
                               
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

        {!! $forms->links() !!}  
        @if ($forms->total() > 0)
        <div><small>Showing {{($forms->currentpage()-1)*$forms->perpage()+1}} to {{(($forms->currentpage()-1)*$forms->perpage())+$forms->count()}}
        of  {{$forms->total()}} forms
        </small></div>          
         @endif        

        </div>
    </div>
  
    <form action="" method="get" id="filter" class="form-inline">

        <div class="form-group">
        Session <select class="form-control" name="session">
                
                @foreach($session_array as $session)
                @if($session == \Request('session'))
                   <option selected>{{$session}}</option>
                @else
                    <option>{{$session}}</option>
                @endif
                @endforeach
                        
        </select>
        </div>
        
         <input  class="form-control" placeholder="Form No." type="text" name = "idfilter" value="{{ \Request('idfilter')  }}" rel="filter">


        <div class="form-group">                                
        OT <select class="form-control" name="overtime_slot">
                <option value="">All</option>
                <option value="First" {{  \Request('overtime_slot')  == 'First' ? 'selected' : '' }}>First</option>
                <option value="Second" {{ \Request('overtime_slot') == 'Second' ? 'selected' : '' }}>Second</option>
                <option value="Third"  {{ \Request('overtime_slot') == 'Third' ? 'selected' : '' }}>Third</option>
                <option value="Sittings"  {{ \Request('overtime_slot') == 'Sittings' ? 'selected' : '' }}>Sittings</option>
                 <option value="Non-Sittings"  {{ \Request('overtime_slot') == 'Non-Sittings' ? 'selected' : '' }}>Non-Sittings</option>
        
        </select>
        </div>
         <input  class="form-control" placeholder="dd-mm-yyyy" type="text" name = "datefilter" value="{{ \Request('datefilter')  }}" rel="filter">
        <input  class="form-control" placeholder="Name/Pen" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter">
        <!-- <input  class="form-control" placeholder="Designation" type="text" name = "desigfilter" value="{{ \Request('desigfilter')  }}" rel="filter"> -->

        <input  type="hidden" name = "status" value="{{  \Request('status')   }}" rel="filter">
        <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
        <a href="{{route('admin.my_forms_others.index')}}" class="btn btn-default">Reset</a>
        <button type="submit" class="btn btn-default" rel="filter">Filter</button>
        
    </form>


    <!-- view all -->

    <form action="my_forms_others/getpdf" method="get" id="filter" class="form-inline" target="_blank">

        <div class="form-group">
        Session <select class="form-control" name="session">
                
                @foreach($session_array as $session)
                @if($session == \Request('session'))
                   <option selected>{{$session}}</option>
                @else
                    <option>{{$session}}</option>
                @endif
                @endforeach
                        
        </select>
        </div>
         <input  type="hidden" name = "status" value="{{  \Request('status')   }}" rel="filter">       
        
        <button type="submit" class="btn btn-default" name="viewall" value="viewallhtml">View All (HTML)</button>
         <button type="submit" class="btn btn-default" name="viewall" value="viewallpdf">View All (PDF)</button>
        
    </form>

    <br>
    <!-- Delete old-->
    @if(count($session_array_todel) > 0)
    <form action="my_forms_others/clearold" method="get" id="filter" class="form-inline" onsubmit="return confirm('Do you really want to delete all the forms for this session?');">

        <div class="form-group">
        Session <select class="form-control" name="session2del">
                
                @foreach($session_array_todel as $session)
                @if($session == \Request('session2del'))
                   <option selected>{{$session}}</option>
                @else
                    <option>{{$session}}</option>
                @endif
                @endforeach
                        
        </select>
        </div>
                     
        <button type="submit" class="btn btn-xs btn-danger">Delete All Forms</button>
         
        
    </form>
    @endif
  

  
    </div>

@stop


@section('javascript') 

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
        activeItem: params.status == undefined ? 'Draft' : params.status
    },

    mounted: function () {
        
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

@endsection