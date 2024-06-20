@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.officeTime.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.office-times.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $officeTime->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.groupname') }}
                                    </th>
                                    <td>
                                        {{ $officeTime->groupname }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.fn_from') }}
                                    </th>
                                    <td>
                                        {{ $officeTime->fn_from }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.an_to') }}
                                    </th>
                                    <td>
                                        {{ $officeTime->an_to }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.minutes_for_ot_workingday') }}
                                    </th>
                                    <td>
                                        {{ $officeTime->minutes_for_ot_workingday }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.minutes_for_ot_holiday') }}
                                    </th>
                                    <td>
                                        {{ $officeTime->minutes_for_ot_holiday }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.max_ot_workingday') }}
                                    </th>
                                    <td>
                                        {{ $officeTime->max_ot_workingday }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.max_ot_sittingday') }}
                                    </th>
                                    <td>
                                        {{ $officeTime->max_ot_sittingday }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.max_ot_holiday') }}
                                    </th>
                                    <td>
                                        {{ $officeTime->max_ot_holiday }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.officeTime.fields.office_minutes') }}
                                    </th>
                                    <td>
                                        {{ $officeTime->office_minutes }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.office-times.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection