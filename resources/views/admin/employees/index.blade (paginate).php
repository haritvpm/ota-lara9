@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')



    <h3 class="page-title">@lang('quickadmin.employees.title')</h3>
    @can('employee_create')
    <p>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                       
                        <!-- <th>@lang('quickadmin.employees.fields.srismt')</th> -->
                        <th>@lang('quickadmin.employees.fields.name')</th>
                        <th>@lang('quickadmin.employees.fields.pen')</th>
                        <th>@lang('quickadmin.employees.fields.designation')</th>
                        <th>Type</th>
                        @if(\Auth::user()->isAdmin())
                        <th>ID</th>
                        <th>@lang('quickadmin.employees.fields.categories')</th>
                        <th>@lang('quickadmin.employees.fields.added-by')</th>
                        <th>@lang('quickadmin.employees.fields.desig-display')</th>
                        @endif
                        <th>&nbsp;</th>

                    </tr>
                </thead>

                               <tbody>
                    @if (count($employees) > 0)
                        @foreach ($employees as $employee)
                            <tr data-entry-id="{{ $employee->id }}">
                               
                               
                                <td field-key='name'>{{ $employee->srismt }}. {{ $employee->name }}</td>
                                <td field-key='pen'>{{ $employee->pen }}</td>
                                <td field-key='designation'>{{ $employee->designation->designation or '' }}</td>

                                <td field-key='category'>{{ $employee->category }}</td>

                                @if(\Auth::user()->isAdmin())
                                <td field-key='id'>{{ $employee->id }}</td>
                                <td field-key='categories'>{{ $employee->categories->category or '' }}</td>
                                <td field-key='added_by'>{{ $employee->added_by }}</td>
                                <td field-key='desig_display'>{{ $employee->desig_display }}</td>

                                @endif
                                <td>
                                    @can('employee_view')
                                    <a href="{{ route('admin.employees.show',[$employee->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('employee_edit')
                                    <a href="{{ route('admin.employees.edit',[$employee->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('employee_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.employees.destroy', $employee->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="13">@lang('quickadmin.qa_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>

            </table>
            {!! $employees->links() !!}
            @if ($employees->total() > 0)
            <div><small>Showing {{($employees->currentpage()-1)*$employees->perpage()+1}} to {{(($employees->currentpage()-1)*$employees->perpage())+$employees->count()}}
            of  {{$employees->total()}} employees
            </small></div>          
            @endif
        </div>
    </div>

 <form action="" method="get" id="filter" class="form-inline">
        
        <input  class="form-control" placeholder="Name/Pen" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter">
        @if(\Auth::user()->isAdmin())
         <div class="form-group">                                
        Staff Type <select class="form-control" name="type">
                <option value="all">All</option>
                <option value="Staff" {{  \Request('type')  == 'Staff' ? 'selected' : '' }}>Staff</option>
                <option value="Provisional" {{ \Request('type') == 'Provisional' ? 'selected' : '' }}>Provisional</option>
                <option value="Staff - Admin Data Entry"  {{ \Request('type') == "Staff - Admin Data Entry" ? 'selected' : '' }}>Staff - Admin Data Entry</option>
               
        </select>
        </div>
        Added By <input  class="form-control" placeholder="username" type="text" name = "added_by" value="{{ \Request('added_by')  }}" rel="filter">
        @endif

    
        <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
        <a href="{{route('admin.employees.index')}}" class="btn btn-default">Reset</a>
        <button type="submit" class="btn btn-default" rel="filter">Search</button>
        
    </form>


@stop
