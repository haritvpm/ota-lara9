@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.designations-other.title')</h3>

    <div class="card">
        <div class="card-title">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.designations-other.fields.designation')</th>
                            <td field-key='designation'>{{ $designations_other->designation }}</td>
                        </tr>
                       
                        <tr>
                            <th>@lang('quickadmin.designations-other.fields.rate')</th>
                            <td field-key='rate'>{{ $designations_other->rate }}</td>
                        </tr>
                        
                       
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.designations_others.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
