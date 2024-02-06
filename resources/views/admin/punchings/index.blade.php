@extends('layouts.app')


@section('content')

<style>

.nav>li>a {
    padding-top: 3px;
    padding-bottom: 3px;
}



</style>

        
    <div  id="app">

    @if(!auth()->user()->isAdminorAudit()) 

    <h4 class="page-title">Punching Form</h4>

    <p>

        <a href="{{ route('admin.punchings.create') }}" class="btn btn-warning" data-toggle="tooltip" title="Prepare a form for punching. "  > New Punching Form</a>
    </p>
   
    @endif

    <hr>
    <h4 class="page-title">Index of Forms 
    </h4>

    <div class="panel panel-default">
    <div class="panel-heading">
        @lang('quickadmin.qa_list')
    </div>

    <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped  }}">
            <thead>
                <tr>
                  
                    <th><a href="<?=URL::to('admin/punchings?sort=id')?>">ID</a></th>
                    <th><a href="<?=URL::to('admin/punchings?sort=session')?>">Session</a></th>
                    <th><a href="<?=URL::to('admin/punchings?sort=creator')?>">Created by</a></th>
                  
                    <th><a href="<?=URL::to('admin/punchings?sort=pen')?>">PEN</a></th>
                    <th><a href="<?=URL::to('admin/punchings?sort=pen'
                    )?>">Name</a></th>
                    

                    <th>&nbsp;</th>

                </tr>
            </thead>
            
            <tbody>
                @if ($forms->total() > 0)
                    @foreach ($forms as $form)
                        <tr data-entry-id="{{ $form->id }}">
                           
                          
                            <td ><small> {{ $form->id }}</small> </td>
                          
                                                      
                            <td >{{ $form->session }}</td>
                            <td >
                                <small>{{$form->creator}}</small>
                            </td>
                            <td > 
                            <small>{{$form->employee->pen}}</small>
                            </td>
                            <td > 
                            <small>{{$form->employee->name}}</small>
                            

                            </td>
                           
                          
                            <td class="text-nowrap">
                                <a href="{{ route('admin.punchings.show',[$form->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view') </a>  <small>{{$form->overtimes()->count()}} </small>
                                
                                <!-- @unless( Auth::user()->isAdminorAudit())                                
                                @if( Auth::user()->username == $form->owner)
                                <a href="{{ route('admin.punchings.edit',[$form->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                                               
                                {!! Form::open(array(
                                  
                                    'style' => 'display: inline-block;',
                                    'method' => 'DELETE',
                                    'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                    'route' => ['admin.punchings.destroy', $form->id])) !!}
                                {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                {!! Form::close() !!}
                                @endif
                                @endunless -->
                               
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
                <option value="First" {{  \Request('overtime_slot')  == 'First' ? 'selected' : '' }}>First</option>
                <option value="Second" {{ \Request('overtime_slot') == 'Second' ? 'selected' : '' }}>Second</option>
                <option value="Third"  {{ \Request('overtime_slot') == 'Third' ? 'selected' : '' }}>Third</option>
                <option value="Sittings"  {{ \Request('overtime_slot') == 'Sittings' ? 'selected' : '' }}>Sittings</option>
                 <option value="Additional"  {{ \Request('overtime_slot') == 'Additional' ? 'selected' : '' }}>Additional</option>
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
         <input  class="form-control" placeholder="WorkNature" type="text" name = "worknaturefilter" value="{{\Request('worknaturefilter')}}" rel="filter">
        <input  class="form-control" placeholder="Remarks|nonempty" type="text" name = "remarksfilter" value="{{\Request('remarksfilter')}}" rel="filter">
        <input  class="form-control" placeholder="submittedby" type="text" name = "submittedbyfilter" value="{{\Request('submittedbyfilter')}}" rel="filter">

        @endif
       
        <a href="{{route('admin.punchings.index')}}" class="btn btn-default">Reset</a>

        <button type="submit" class="btn btn-default" rel="filter">Search</button>
        
    </form>
     </div>

    <br>
    <!-- view all -->

    @if(Auth::user()->isAdminorAudit())
    <form action="punchings/getpdf" method="get" id="filter" class="form-inline" target="_blank">

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
       <!--  <div class="form-group">
         <input  class="form-control" placeholder="Form No." type="text" name = "idfilter" value="{{ \Request('idfilter')  }}" rel="filter">
        </div> -->

        @if(Auth::user()->isAdminorAudit())
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
                <option value="First" {{  \Request('overtime_slot')  == 'First' ? 'selected' : '' }}>First</option>
                <option value="Second" {{ \Request('overtime_slot') == 'Second' ? 'selected' : '' }}>Second</option>
                <option value="Third"  {{ \Request('overtime_slot') == 'Third' ? 'selected' : '' }}>Third</option>
                <option value="Sittings"  {{ \Request('overtime_slot') == 'Sittings' ? 'selected' : '' }}>Sittings</option>
                <option value="Additional"  {{ \Request('overtime_slot') == 'Additional' ? 'selected' : '' }}>Additional</option>
                 <option value="Non-Sittings"  {{ \Request('overtime_slot') == 'Non-Sittings' ? 'selected' : '' }}>Non-Sittings</option>
        
        </select>
        </div>
        Date <input  class="form-control" placeholder="dd-mm-yyyy" type="text" name = "datefilter" value="{{ \Request('datefilter')  }}" rel="filter">
        <input  class="form-control" placeholder="Name/Pen" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter">
        <input  class="form-control" placeholder="Designation" type="text" name = "desigfilter" value="{{ \Request('desigfilter')  }}" rel="filter">

        <input  type="hidden" name = "status" value="{{  \Request('status')   }}" rel="filter">
        <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
        <a href="{{route('admin.punchings.index')}}" class="btn btn-default">Reset</a>
        
        <button type="submit" class="btn btn-default" name="viewall" value="viewallhtml">View All (HTML)</button>
         <button type="submit" class="btn btn-default" name="viewall" value="viewallpdf">View All (PDF)</button>
        
    </form>
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

@endsection