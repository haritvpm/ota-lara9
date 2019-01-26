@extends('layouts.app')


@section('content')

<style>

.nav>li>a {
    padding-top: 3px;
    padding-bottom: 3px;
}
</style>

    <h3 class="page-title">Exemption</h3>
    
    <div  id="app">
    
    @if(auth()->user()->isSimpleUser()||\Auth::user()->isAdmin())
    <p>
        <a href="{{ route('admin.myexemptionforms.create') }}" class="btn btn-info"><i class="fa fa-file-text-o"></i> New Application</a>
       
    </p>
    @endif
      

    <hr>
   @if(!auth()->user()->isAudit()) 
    <p>
        
        <ul class="nav nav-pills ">
        @if(!auth()->user()->isAdmin() && !auth()->user()->isServices())
        <li @click="setActive('todo')" :class="{ active: isActive('todo') }"><a href="#">ToDo</a></li>
        @else
        <li @click="setActive('all')" :class="{ active: isActive('all') }"><a href="#">All</a></li>
        
        <li @click="setActive('Draft')" :class="{ active: isActive('Draft') }"><a href="#">Draft</a></li>
        @endif
         <!-- @if($to_approve != -1) -->
        <!-- <li @click="setActive('To_approve')" :class="{ active: isActive('To_approve') }"><a href="#">To Approve</a></li> -->
        <!-- @endif -->
        
        
        @if($pending_approval != -1)
        <li @click="setActive('Pending')" :class="{ active: isActive('Pending') }"><a href="#">Sent for Approval</a></li>
        @endif
       
        <li @click="setActive('Submitted')" :class="{ active: isActive('Submitted') }"><a href="#">Submitted to Services-A</a></li>

      <!--   @if(auth()->user()->isAdmin()) 
        <li @click="setActive('')" :class="{ active: isActive('') }"><a href="#">All</a></li>
        @endif -->

        </ul>
    </p>
    @endif

    <div class="panel panel-default">
    <div class="panel-heading">
        @lang('quickadmin.qa_list')
    </div>

    <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped table-condensed }}">
            <thead>
                <tr>
                  
                    <th><a href="<?=URL::to('admin/myexemptionforms?sort=id'.$querystr)?>">ID</a></th>
                   
                    <th><a href="<?=URL::to('admin/myexemptionforms?sort=session'.$querystr)?>">Session</a></th>
                    <th><a href="<?=URL::to('admin/myexemptionforms?sort=creator'.$querystr)?>">Created by</a></th>
                    <th><a href="<?=URL::to('admin/myexemptionforms?sort=owner'.$querystr)?>">Status</a></th>

                    @if(auth()->user()->isAdmin())
                    <th>Submitted on</th>
                    <th>Updated</th>
                    @endif

                    <th>Remarks</th>
                    
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
                                @if(optional($form->created_by)->Title != null)
                                    {{ optional($form->created_by)->Title }}<small> ({{$form->creator}})</small>
                                @else
                                    <small>{{$form->creator}}</small>
                                @endif
                            @endif
                            </td>

                             <td>
                               
                            @if($form->owner == Auth::user()->username)
                                @if($form->owner == 'admin')
                                 <span class="text-default"><i class="fa fa-thumbs-up" style="color:green"></i> @Services</span> 
                                @else
                                    @if($form->owner != $form->creator)
                                    <span class="text-default"><i class="fa fa-eye" style="color:red"></i> To approve</span> 
                                    @else
                                    <span class="text-default"><i class="fa fa-edit"></i> Draft</span>
                                    @endif    
                                @endif
                            @else
                                @if($form->owner == 'admin')
                                <span class="text-default"><i class="fa fa-thumbs-up" style="color:green"></i> <small>Submitted to Services</small></span> 
                                @else
                                    @if($form->owner == $form->creator)
                                     <span class="text-default"><i class="fa fa-edit"></i> Draft</span>
                                    @else
<i class="fa fa-mail-forward" style="color:blue"></i> <small>at <span class="text-default">{{ optional($form->owned_by)->Title  ?? $form->owner }} {{ optional($form->owned_by)->displayname}}</span></small>
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
                            {{ date('d-m-Y', strtotime($form->submitted_on)) }}
                            @endif
                            </small></td>
                            <td ><small>
                           {{$form->updated_at}}
                            </small></td>
                            @endif

                            <td>
                                <small>
                                @php
                               echo str_limit( $form->remarks , 20)
                               @endphp
                               </small>
                              
                            </td> 


                            <td>
                                <a href="{{ route('admin.myexemptionforms.show',[$form->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                                               
                                
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

        <input  class="form-control" placeholder="Name/Pen" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter">
        
        <a href="{{route('admin.pa2mlaforms.index')}}" class="btn btn-default">Reset</a>
        <button type="submit" class="btn btn-default" rel="filter">Filter</button>
        
    </form>

    </div>

    @if(auth()->user()->isAdmin() || auth()->user()->isServices())
       
    <br>    

    <div>
        
        <form action="{{url('admin/myexemptionforms/download_emp')}}" method="get" class="form-inline">
           
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
            <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
            <button type="submit" class="btn btn-danger" rel="filter"><span class="glyphicon glyphicon-save"></span> </button>

        </form>

    </div> 
    @endif 

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
        activeItem: params.status == undefined ? 'todo' : params.status
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