@extends('layouts.admin')
@section('content')
<div class="content">
    @can('shift_time_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.shift-times.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.shiftTime.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.shiftTime.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ShiftTime">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.shiftTime.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shiftTime.fields.groupname') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shiftTime.fields.shift_minutes') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shiftTime.fields.minutes_for_ot') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shiftTimes as $key => $shiftTime)
                                    <tr data-entry-id="{{ $shiftTime->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $shiftTime->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shiftTime->groupname ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shiftTime->shift_minutes ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shiftTime->minutes_for_ot ?? '' }}
                                        </td>
                                        <td>
                                            @can('shift_time_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.shift-times.show', $shiftTime->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('shift_time_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.shift-times.edit', $shiftTime->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('shift_time_delete')
                                                <form action="{{ route('admin.shift-times.destroy', $shiftTime->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('shift_time_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.shift-times.massDestroy') }}",
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
  let table = $('.datatable-ShiftTime:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection