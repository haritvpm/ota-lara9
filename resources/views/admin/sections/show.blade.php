@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.section.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sections.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.section.fields.id') }}
                        </th>
                        <td>
                            {{ $section->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.section.fields.name') }}
                        </th>
                        <td>
                            {{ $section->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.section.fields.officer') }}
                        </th>
                        <td>
                            {{ $section->officer->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sections.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection