@extends('layouts.app')
@section('content')
@can('device_access')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.devices.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.device.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.device.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable ">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.device.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.device.fields.device') }}
                        </th>
                        <th>
                            {{ trans('cruds.device.fields.loc_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.device.fields.entry_name') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devices as $key => $device)
                        <tr data-entry-id="{{ $device->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $device->id ?? '' }}
                            </td>
                            <td>
                                {{ $device->device ?? '' }}
                            </td>
                            <td>
                                {{ $device->loc_name ?? '' }}
                            </td>
                            <td>
                                {{ $device->entry_name ?? '' }}
                            </td>
                            <td>
                                @can('device_access')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.devices.show', $device->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('device_access')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.devices.edit', $device->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('device_access')
                                    <form action="{{ route('admin.devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@section('scripts')
@parent
<script>
  
</script>
@endsection