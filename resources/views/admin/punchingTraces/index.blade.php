@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.punchingTrace.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-PunchingTrace">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.punchingTrace.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.punchingTrace.fields.aadhaarid') }}
                    </th>
                    <th>
                        {{ trans('cruds.punchingTrace.fields.org_emp_code') }}
                    </th>
                    <th>
                        {{ trans('cruds.punchingTrace.fields.device') }}
                    </th>
                    <th>
                        {{ trans('cruds.punchingTrace.fields.attendance_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.punchingTrace.fields.auth_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.punchingTrace.fields.err_code') }}
                    </th>
                    <th>
                        {{ trans('cruds.punchingTrace.fields.att_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.punchingTrace.fields.att_time') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.punching-traces.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'aadhaarid', name: 'aadhaarid' },
{ data: 'org_emp_code', name: 'org_emp_code' },
{ data: 'device', name: 'device' },
{ data: 'attendance_type', name: 'attendance_type' },
{ data: 'auth_status', name: 'auth_status' },
{ data: 'err_code', name: 'err_code' },
{ data: 'att_date', name: 'att_date' },
{ data: 'att_time', name: 'att_time' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-PunchingTrace').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection