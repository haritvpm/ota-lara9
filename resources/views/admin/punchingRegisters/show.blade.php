@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.punchingRegister.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.punching-registers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.id') }}
                        </th>
                        <td>
                            {{ $punchingRegister->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.date') }}
                        </th>
                        <td>
                            {{ $punchingRegister->date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.employee') }}
                        </th>
                        <td>
                            {{ $punchingRegister->employee->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.punchin') }}
                        </th>
                        <td>
                            {{ $punchingRegister->punchin->date ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.duration') }}
                        </th>
                        <td>
                            {{ $punchingRegister->duration }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.flexi') }}
                        </th>
                        <td>
                            {{ App\PunchingRegister::FLEXI_SELECT[$punchingRegister->flexi] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.grace_min') }}
                        </th>
                        <td>
                            {{ $punchingRegister->grace_min }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.extra_min') }}
                        </th>
                        <td>
                            {{ $punchingRegister->extra_min }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.punching-registers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection