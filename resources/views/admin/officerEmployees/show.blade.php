@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.officerEmployee.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.officer-employees.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.officerEmployee.fields.id') }}
                        </th>
                        <td>
                            {{ $officerEmployee->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.officerEmployee.fields.officer') }}
                        </th>
                        <td>
                            {{ $officerEmployee->officer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.officerEmployee.fields.employee') }}
                        </th>
                        <td>
                            {{ $officerEmployee->employee->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.officerEmployee.fields.date_from') }}
                        </th>
                        <td>
                            {{ $officerEmployee->date_from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.officerEmployee.fields.date_to') }}
                        </th>
                        <td>
                            {{ $officerEmployee->date_to }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.officer-employees.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection