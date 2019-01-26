@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.exemptions.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.exemptions.fields.pen')</th>
                            <td field-key='pen'>{{ $exemption->pen }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.exemptions.fields.designation')</th>
                            <td field-key='designation'>{{ $exemption->designation }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.exemptions.fields.worknature')</th>
                            <td field-key='worknature'>{{ $exemption->worknature }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.exemptions.fields.exemptionform')</th>
                            <td field-key='exemptionform'>{{ $exemption->exemptionform->session or '' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.exemptions.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
