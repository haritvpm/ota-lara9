@extends('layouts.app')
@section('content')
@can('officer_employee_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.officer-employees.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.officerEmployee.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modalfortrait', ['model' => 'OfficerEmployee', 'route' => 'admin.officer-employees.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.officerEmployee.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable ">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.officerEmployee.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.officerEmployee.fields.officer') }}
                        </th>
                        <th>
                            {{ trans('cruds.officerEmployee.fields.employee') }}
                        </th>
                        <th>
                            {{ trans('cruds.employee.fields.aadhaarid') }}
                        </th>
                        <th>
                            {{ trans('cruds.officerEmployee.fields.date_from') }}
                        </th>
                        <th>
                            {{ trans('cruds.officerEmployee.fields.date_to') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($officerEmployees as $key => $officerEmployee)
                        <tr data-entry-id="{{ $officerEmployee->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $officerEmployee->id ?? '' }}
                            </td>
                            <td>
                                {{ $officerEmployee->officer->name ?? '' }} ( {{ $officerEmployee->officer->username ?? '' }})
                            </td>
                            <td>
                                {{ $officerEmployee->employee->name ?? '' }}
                            </td>
                            <td>
                                {{ $officerEmployee->employee->aadhaarid ?? '' }}
                            </td>
                            <td>
                                {{ $officerEmployee->date_from ?? '' }}
                            </td>
                            <td>
                                {{ $officerEmployee->date_to ?? '' }}
                            </td>
                            <td>
                                @can('officer_employee_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.officer-employees.show', $officerEmployee->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('officer_employee_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.officer-employees.edit', $officerEmployee->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('officer_employee_delete')
                                    <form action="{{ route('admin.officer-employees.destroy', $officerEmployee->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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



@endsection
@section('javascript')
@parent
<script>
 
</script>
@endsection