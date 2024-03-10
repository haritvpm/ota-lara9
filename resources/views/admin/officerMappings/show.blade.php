@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.officerMapping.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.officer-mappings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.officerMapping.fields.id') }}
                        </th>
                        <td>
                            {{ $officerMapping->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.officerMapping.fields.section_or_officer_user') }}
                        </th>
                        <td>
                            {{ $officerMapping->section_or_officer_user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.officerMapping.fields.controlling_officer_user') }}
                        </th>
                        <td>
                            {{ $officerMapping->controlling_officer_user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.officer-mappings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection