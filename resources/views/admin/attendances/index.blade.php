@extends('layouts.app')
@section('content')
@can('attendance_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.attendances.create') }}">
                {{ trans('global.add') }} {{ trans('quickadmin.attendance.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modalfortrait', ['model' => 'Attendance', 'route' => 'admin.attendances.parseCsvImportCustom'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('quickadmin.attendance.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped datatable datatable-Attendance">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('quickadmin.attendance.fields.id') }}
                        </th>
                        <th>
                            As per O/S
                        </th>
                      
                        <th>
                            As per us
                        </th>
                        <th>
                            {{ trans('quickadmin.attendance.fields.session') }}
                        </th>
                        <th>
                            {{ trans('quickadmin.attendance.fields.present_dates') }}
                        </th>
                        <th>
                            {{ trans('quickadmin.attendance.fields.total') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $key => $attendance)
                        <tr data-entry-id="{{ $attendance->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $attendance->id ?? '' }}
                            </td>
                            <td>
                                {{ $attendance->name ?? '' }} <br>
                                {{ $attendance->pen ?? '' }}<br>
                                {{ $attendance->designation ?? '' }} <br>
                                {{ $attendance->section ?? '' }} 
                            </td>
                           
                            <td>
                                {{ $attendance->employee->name  ?? 'Please edit and select' }}<br>
                                {{ $attendance->employee->pen ?? '' }}
                            </td>

                            <td>
                                {{ $attendance->session->name ?? '' }}
                            </td>

                            <td>
                                {{ $attendance->present_dates ?? '' }}
                            </td>
                            <td class="text-center">
                               <strong> {{ $attendance->total ?? '' }}</strong>
                            </td>
                            <td>
                                @can('attendance_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.attendances.show', $attendance->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('attendance_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.attendances.edit', $attendance->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('attendance_delete')
                                    <form action="{{ route('admin.attendances.destroy', $attendance->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <form action="" method="get" id="filter" class="form-inline">
		<div class="form-group">
            Session <select class="form-control" name="session">

                @foreach($sessions as $sess)
                @if($sess == \Request('session'))
                <option selected>{{$sess}}</option>
                @else
                <option>{{$sess}}</option>
                @endif
                @endforeach

            </select>
        </div>
      
       
        <button type="submit" name="action" value="view" class="btn btn-success" rel="filter"><span class="glyphicon glyphicon-search"></span> View</button>
        <button type="submit" name="action" value="deleteall" class="btn btn-danger">Delete All</button>
      
        </form>


    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Attendance:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection