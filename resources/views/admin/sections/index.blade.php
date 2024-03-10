@extends('layouts.app')
@section('content')
@can('section_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.sections.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.section.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modalfortrait', ['model' => 'Section', 'route' => 'admin.sections.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.section.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.section.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.section.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.section.fields.officer') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $key => $section)
                        <tr data-entry-id="{{ $section->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $section->id ?? '' }}
                            </td>
                            <td>
                                {{ $section->name ?? '' }}
                            </td>
                            <td>
                                {{ $section->officer->name ?? '' }}
                            </td>
                            <td>
                                @can('section_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.sections.show', $section->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('section_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.sections.edit', $section->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('section_delete')
                                    <form action="{{ route('admin.sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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