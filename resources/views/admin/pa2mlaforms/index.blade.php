@extends('layouts.app')


@section('content')

    <h3 class="page-title">PA to MLA @lang('quickadmin.forms.title')</h3>
    
    <div  id="app">
    
    <p>
        <a href="{{ route('admin.pa2mlaforms.create') }}" class="btn btn-success"><i class="fa fa-file-text-o"></i> New Form</a>
       
    </p>
      
     

    <div class="">
     <div class="">
        <table class="table table-bordered table-striped table-sm }}">
            <thead>
                <tr>
                  
                    <th><a href="<?=URL::to('admin/pa2mlaforms?sort=id'.$querystr)?>">ID</a></th>
                    <th>Formno</th>
                    <th><a href="<?=URL::to('admin/mpa2mlaforms?sort=session'.$querystr)?>">Session</a></th>
               
                    <th><a href="<?=URL::to('admin/pa2mlaforms?sort=duty_date'.$querystr)?>">Duty Date</a></th>
                     
                    <th>Remarks</th>
                    <th>Last Updated</th>
                    <th>&nbsp;</th>

                </tr>
            </thead>
            
            <tbody>
                @if (count($forms) > 0)
                    @foreach ($forms as $form)
                        <tr data-entry-id="{{ $form->id }}">
                           

                            <td >{{ $form->id }}</td>
                            <td >{{ $form->form_no }}</td>
                            <td >{{ $form->session }}</td>
                                                      
                            <td>
                                                      
                                {{ $form->date_from }} to {{ $form->date_to }}      
                           
                            </td>
                                                       
                           
                            
                            <td >{{ \Illuminate\Support\Str::limit($form->remarks, 30)  }}</td>
                            <td >{{ date('d-m-Y', strtotime($form->updated_at)) }}</td>

                            <td>
                                <a href="{{ route('admin.pa2mlaforms.show',[$form->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_view')</a> <small>({{$form->overtimes()->count()}}) </small>
                                                                
                              <!--   @if( Auth::user()->username == $form->owner)
                                <a href="{{ route('admin.pa2mlaforms.edit',[$form->id]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_edit')</a> -->
                                                               
                               <!--  {!! Form::open(array(
                                  
                                    'style' => 'display: inline-block;',
                                    'method' => 'DELETE',
                                    'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                    'route' => ['admin.pa2mlaforms.destroy', $form->id])) !!}
                                {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
                                {!! Form::close() !!}
                                @endif -->
                               
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

        
        Date <input  class="form-control" placeholder="dd-mm-yyyy" type="text" name = "datefilter" value="{{ \Request('datefilter')  }}" rel="filter">
        <input  class="form-control" placeholder="Name/Pen" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter">
        
        <a href="{{route('admin.pa2mlaforms.index')}}" class="btn btn-default">Reset</a>
        <button type="submit" class="btn btn-default" rel="filter">Filter</button>
        
    </form>

  
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
        activeItem: params.status == undefined ? 'all' : params.status
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