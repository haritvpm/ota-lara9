@extends('layouts.app')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.shiftTime.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.shift-times.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.shiftTime.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $shiftTime->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.shiftTime.fields.groupname') }}
                                    </th>
                                    <td>
                                        {{ $shiftTime->groupname }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.shiftTime.fields.shift_minutes') }}
                                    </th>
                                    <td>
                                        {{ $shiftTime->shift_minutes }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.shiftTime.fields.minutes_for_ot') }}
                                    </th>
                                    <td>
                                        {{ $shiftTime->minutes_for_ot }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.shift-times.index') }}">
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