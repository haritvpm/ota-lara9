@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.device.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.devices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.device.fields.id') }}
                        </th>
                        <td>
                            {{ $device->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.device.fields.device') }}
                        </th>
                        <td>
                            {{ $device->device }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.device.fields.loc_name') }}
                        </th>
                        <td>
                            {{ $device->loc_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.device.fields.entry_name') }}
                        </th>
                        <td>
                            {{ $device->entry_name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.devices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection