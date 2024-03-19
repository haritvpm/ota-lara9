@extends('layouts.app')
@section('content')
@can('section_employee_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.section-employees.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.sectionEmployee.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modalfortrait', ['model' => 'SectionEmployee', 'route' => 'admin.section-employees.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.sectionEmployee.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SectionEmployee">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.sectionEmployee.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.sectionEmployee.fields.section_or_offfice') }}
                    </th>
                    <th>
                        {{ trans('cruds.sectionEmployee.fields.employee') }}
                    </th>
                    <th>
                        {{ trans('cruds.employee.fields.aadhaarid') }}
                    </th>
                    <th>
                        {{ trans('cruds.sectionEmployee.fields.date_from') }}
                    </th>
                    <th>
                        {{ trans('cruds.sectionEmployee.fields.date_to') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@stop
@section('javascript')
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
    ajax: "{{ route('admin.section-employees.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'section_or_offfice_name', name: 'section_or_offfice.name' },
{ data: 'employee_name', name: 'employee.name' },
{ data: 'employee.aadhaarid', name: 'employee.aadhaarid' },
{ data: 'date_from', name: 'date_from' },
{ data: 'date_to', name: 'date_to' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-SectionEmployee').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@stop