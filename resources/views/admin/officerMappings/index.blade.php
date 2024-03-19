@extends('layouts.app')
@section('content')
@can('officer_mapping_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.officer-mappings.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.officerMapping.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modalfortrait', ['model' => 'OfficerMapping', 'route' => 'admin.officer-mappings.parseCsvImport'])

            
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.officerMapping.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.officerMapping.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.officerMapping.fields.section_or_officer_user') }}
                        </th>
                        <th>
                            {{ trans('cruds.officerMapping.fields.controlling_officer_user') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($officerMappings as $key => $officerMapping)
                        <tr data-entry-id="{{ $officerMapping->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $officerMapping->id ?? '' }}
                            </td>
                            <td>
                                {{ $officerMapping->section_or_officer_user->name ?? '' }} ( {{ $officerMapping->section_or_officer_user->username ?? '' }})
                            </td>
                            <td>
                                {{ $officerMapping->controlling_officer_user->name ?? '' }} ( {{ $officerMapping->controlling_officer_user->username ?? '' }})
                            </td>
                            <td>
                                @can('officer_mapping_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.officer-mappings.show', $officerMapping->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('officer_mapping_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.officer-mappings.edit', $officerMapping->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('officer_mapping_delete')
                                    <form action="{{ route('admin.officer-mappings.destroy', $officerMapping->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@section('javascript')
@parent
<script>
  
</script>
@endsection