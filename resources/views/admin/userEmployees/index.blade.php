@extends('layouts.app')
@section('content')
@can('user_employee_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.user-employees.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.userEmployee.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modalfortrait', ['model' => 'UserEmployee', 'route' => 'admin.user-employees.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.userEmployee.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.userEmployee.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.userEmployee.fields.user') }}
                        </th>
                        <th>
                            {{ trans('cruds.userEmployee.fields.employee') }}
                        </th>
                        <th>
                            {{ trans('cruds.employee.fields.aadhaarid') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userEmployees as $key => $userEmployee)
                        <tr data-entry-id="{{ $userEmployee->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $userEmployee->id ?? '' }}
                            </td>
                            <td>
                                {{ $userEmployee->user->name ?? '' }} (    {{ $userEmployee->user->username ?? '' }})
                            </td>
                            <td>
                                {{ $userEmployee->employee->name ?? '' }}
                            </td>
                            <td>
                                {{ $userEmployee->employee->aadhaarid ?? '' }}
                            </td>
                            <td>
                                @can('user_employee_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.user-employees.show', $userEmployee->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('user_employee_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.user-employees.edit', $userEmployee->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('user_employee_delete')
                                    <form action="{{ route('admin.user-employees.destroy', $userEmployee->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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