@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.exemptionforms.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.exemptionforms.fields.session')</th>
                            <td field-key='session'>{{ $exemptionform->session }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.exemptionforms.fields.creator')</th>
                            <td field-key='creator'>{{ $exemptionform->creator }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.exemptionforms.fields.owner')</th>
                            <td field-key='owner'>{{ $exemptionform->owner }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.exemptionforms.fields.form-no')</th>
                            <td field-key='form_no'>{{ $exemptionform->form_no }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.exemptionforms.fields.submitted-names')</th>
                            <td field-key='submitted_names'>{{ $exemptionform->submitted_names }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.exemptionforms.fields.submitted-by')</th>
                            <td field-key='submitted_by'>{{ $exemptionform->submitted_by }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.exemptionforms.fields.submitted-on')</th>
                            <td field-key='submitted_on'>{{ $exemptionform->submitted_on }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.exemptionforms.fields.remarks')</th>
                            <td field-key='remarks'>{{ $exemptionform->remarks }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#exemptions" aria-controls="exemptions" role="tab" data-toggle="tab">Exemptions</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="exemptions">
<table class="table table-bordered table-striped {{ count($exemptions) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.exemptions.fields.pen')</th>
                        <th>@lang('quickadmin.exemptions.fields.designation')</th>
                        <th>@lang('quickadmin.exemptions.fields.worknature')</th>
                        <th>@lang('quickadmin.exemptions.fields.exemptionform')</th>
                                                <th>&nbsp;</th>

        </tr>
    </thead>

    <tbody>
        @if (count($exemptions) > 0)
            @foreach ($exemptions as $exemption)
                <tr data-entry-id="{{ $exemption->id }}">
                    <td field-key='pen'>{{ $exemption->pen }}</td>
                                <td field-key='designation'>{{ $exemption->designation }}</td>
                                <td field-key='worknature'>{{ $exemption->worknature }}</td>
                                <td field-key='exemptionform'>{{ $exemption->exemptionform->session or '' }}</td>
                                                                <td>
                                    @can('view')
                                    <a href="{{ route('exemptions.show',[$exemption->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('edit')
                                    <a href="{{ route('exemptions.edit',[$exemption->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['exemptions.destroy', $exemption->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.exemptionforms.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
