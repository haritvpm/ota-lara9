@extends('layouts.admin')
@section('content')
<div class="content">
    @can('office_time_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.office-times.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.officeTime.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.officeTime.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-OfficeTime">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.groupname') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.fn_from') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.an_to') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.minutes_for_ot_workingday') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.minutes_for_ot_holiday') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.max_ot_workingday') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.max_ot_sittingday') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.max_ot_holiday') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.office_minutes') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($officeTimes as $key => $officeTime)
                                    <tr data-entry-id="{{ $officeTime->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $officeTime->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $officeTime->groupname ?? '' }}
                                        </td>
                                        <td>
                                            {{ $officeTime->fn_from ?? '' }}
                                        </td>
                                        <td>
                                            {{ $officeTime->an_to ?? '' }}
                                        </td>
                                        <td>
                                            {{ $officeTime->minutes_for_ot_workingday ?? '' }}
                                        </td>
                                        <td>
                                            {{ $officeTime->minutes_for_ot_holiday ?? '' }}
                                        </td>
                                        <td>
                                            {{ $officeTime->max_ot_workingday ?? '' }}
                                        </td>
                                        <td>
                                            {{ $officeTime->max_ot_sittingday ?? '' }}
                                        </td>
                                        <td>
                                            {{ $officeTime->max_ot_holiday ?? '' }}
                                        </td>
                                        <td>
                                            {{ $officeTime->office_minutes ?? '' }}
                                        </td>
                                        <td>
                                            @can('office_time_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.office-times.show', $officeTime->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('office_time_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.office-times.edit', $officeTime->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('office_time_delete')
                                                <form action="{{ route('admin.office-times.destroy', $officeTime->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                </div>
            </div>



        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('office_time_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.office-times.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-OfficeTime:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection