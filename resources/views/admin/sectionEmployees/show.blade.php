@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.sectionEmployee.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.section-employees.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.sectionEmployee.fields.id') }}
                        </th>
                        <td>
                            {{ $sectionEmployee->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sectionEmployee.fields.section_or_offfice') }}
                        </th>
                        <td>
                            {{ $sectionEmployee->section_or_offfice->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sectionEmployee.fields.employee') }}
                        </th>
                        <td>
                            {{ $sectionEmployee->employee->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sectionEmployee.fields.date_from') }}
                        </th>
                        <td>
                            {{ $sectionEmployee->date_from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sectionEmployee.fields.date_to') }}
                        </th>
                        <td>
                            {{ $sectionEmployee->date_to }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.section-employees.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection