@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.overtimes-others.title')</h3>

    <div class="card">
        <div class="card-title">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.overtimes-others.fields.pen')</th>
                            <td field-key='pen'>{{ $overtimes_other->pen }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes-others.fields.designation')</th>
                            <td field-key='designation'>{{ $overtimes_other->designation }}</td>
                        </tr>
                       
                        <tr>
                            <th>@lang('quickadmin.overtimes-others.fields.from')</th>
                            <td field-key='from'>{{ $overtimes_other->from }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes-others.fields.to')</th>
                            <td field-key='to'>{{ $overtimes_other->to }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes-others.fields.count')</th>
                            <td field-key='count'>{{ $overtimes_other->count }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes-others.fields.worknature')</th>
                            <td field-key='worknature'>{{ $overtimes_other->worknature }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.overtimes_others.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
