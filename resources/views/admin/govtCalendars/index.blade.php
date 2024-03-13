@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.govtCalendar.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-GovtCalendar">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.date') }}
                        </th>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.govtholidaystatus') }}
                        </th>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.restrictedholidaystatus') }}
                        </th>
                        <!-- <th>
                            {{ trans('cruds.govtCalendar.fields.bankholidaystatus') }}
                        </th> -->
                        <th>
                            {{ trans('cruds.govtCalendar.fields.festivallist') }}
                        </th>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.success_attendance_fetched') }}
                        </th>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.success_attendance_lastfetchtime') }}
                        </th>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.success_attendance_rows_fetched') }}
                        </th>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.attendance_today_trace_fetched') }}
                        </th>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.attendancetodaytrace_lastfetchtime') }}
                        </th>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.attendance_today_trace_rows_fetched') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($govtCalendars as $key => $govtCalendar)
                        <tr data-entry-id="{{ $govtCalendar->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $govtCalendar->id ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->date ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->govtholidaystatus ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->restrictedholidaystatus ?? '' }}
                            </td>
                            <!-- <td>
                                {{ $govtCalendar->bankholidaystatus ?? '' }}
                            </td> -->
                            <td>
                                {{ $govtCalendar->festivallist ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->success_attendance_fetched ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->success_attendance_lastfetchtime ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->success_attendance_rows_fetched ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->attendance_today_trace_fetched ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->attendancetodaytrace_lastfetchtime ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->attendance_today_trace_rows_fetched ?? '' }}
                            </td>
                            <td>
                                @can('govt_calendar_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.govt-calendars.show', $govtCalendar->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('govt_calendar_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.govt-calendars.edit', $govtCalendar->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan


                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
  let table = $('.datatable-GovtCalendar:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection