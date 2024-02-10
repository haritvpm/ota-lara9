@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.categories.title')</h3>
    <!-- @can('category_create')
    <p>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan -->

    
    <p>These categories are hard-coded in OT Processor exe.</p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ">
                <thead>
                    <tr>
                        <th>
                                        {{ trans('cruds.category.fields.id') }}
                                    </th>

                        <th>@lang('quickadmin.categories.fields.category')</th>
                      
                      
                        <th>
                                        {{ trans('cruds.category.fields.punching') }}
                                    </th>
                        <th>
                            &nbsp;
                        </th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($categories) > 0)
                        @foreach ($categories as $category)
                            <tr data-entry-id="{{ $category->id }}">
                            <td>
                                            {{ $category->id ?? '' }}
                            </td>

                            <td field-key='category'>{{ $category->category }}</td>

                            <td>
                                <span style="display:none">{{ $category->punching ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $category->punching ? 'checked' : '' }}>
                            </td>
                            <td>
                                <!-- @can('category_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.categories.show', $category->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan -->

                                @can('category_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.categories.edit', $category->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                <!-- @can('category_delete')
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan -->

                            </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">@lang('quickadmin.qa_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
   <!--  <script>
        @can('category_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.categories.mass_destroy') }}';
        @endcan

    </script> -->
@endsection