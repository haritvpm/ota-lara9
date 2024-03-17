@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#calenderModal" data-whatever="@getbootstrap"><i class="fa fa-refresh" aria-hidden="true"></i>Upload with Govt Calender </button>

<div class="modal fade" id="calenderModal" tabindex="-1" role="dialog" aria-labelledby="calenderModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="calenderModalLabel">Paste From API Response for a month</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{url('admin/govt-calendars/updatemonth')}}" method="post" id="filter" class="form-inline">
      @csrf
      <div class="form-group">                                
        <textarea id="jsonfromgovtsite" name="jsonfromgovtsite" rows="2" cols="50" placeholder="sync with reponse json from https://www.kerala.gov.in/showcalendar/<month>. click next to get month's data"></textarea>
        </div>
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>





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
                                {{ $govtCalendar->success_attendance_fetched ? 'YES' :'NO' }}
                                
                            </td>
                            <td>
                                {{ $govtCalendar->success_attendance_lastfetchtime ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->success_attendance_rows_fetched ?? '' }}
                            </td>
                            <td>
                                {{ $govtCalendar->attendance_today_trace_fetched ? 'YES' :'NO' }}
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

                                    <a href="{{ route('admin.govt-calendars.fetch',$govtCalendar->date ) }}"  class="btn btn-sm btn-danger"><i class="fa fa-refresh" aria-hidden="true"></i>Fetch</a>


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