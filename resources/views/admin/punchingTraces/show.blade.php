@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.punchingTrace.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.punching-traces.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingTrace.fields.id') }}
                        </th>
                        <td>
                            {{ $punchingTrace->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingTrace.fields.aadhaarid') }}
                        </th>
                        <td>
                            {{ $punchingTrace->aadhaarid }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingTrace.fields.org_emp_code') }}
                        </th>
                        <td>
                            {{ $punchingTrace->org_emp_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingTrace.fields.device') }}
                        </th>
                        <td>
                            {{ $punchingTrace->device }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingTrace.fields.attendance_type') }}
                        </th>
                        <td>
                            {{ $punchingTrace->attendance_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingTrace.fields.auth_status') }}
                        </th>
                        <td>
                            {{ $punchingTrace->auth_status }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingTrace.fields.err_code') }}
                        </th>
                        <td>
                            {{ $punchingTrace->err_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingTrace.fields.att_date') }}
                        </th>
                        <td>
                            {{ $punchingTrace->att_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingTrace.fields.att_time') }}
                        </th>
                        <td>
                            {{ $punchingTrace->att_time }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.punching-traces.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection