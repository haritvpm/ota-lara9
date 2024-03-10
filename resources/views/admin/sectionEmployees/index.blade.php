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
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable ">
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
                <tbody>
                    @foreach($sectionEmployees as $key => $sectionEmployee)
                        <tr data-entry-id="{{ $sectionEmployee->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $sectionEmployee->id ?? '' }}
                            </td>
                            <td>
                                {{ $sectionEmployee->section_or_offfice->name ?? '' }}
                            </td>
                            <td>
                                {{ $sectionEmployee->employee->name ?? '' }}
                            </td>
                            <td>
                                {{ $sectionEmployee->employee->aadhaarid ?? '' }}
                            </td>
                            <td>
                                {{ $sectionEmployee->date_from ?? '' }}
                            </td>
                            <td>
                                {{ $sectionEmployee->date_to ?? '' }}
                            </td>
                            <td>
                                @can('section_employee_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.section-employees.show', $sectionEmployee->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('section_employee_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.section-employees.edit', $sectionEmployee->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('section_employee_delete')
                                    <form action="{{ route('admin.section-employees.destroy', $sectionEmployee->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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



@stop
@section('javascript')
@parent
<script>


</script>
@stop