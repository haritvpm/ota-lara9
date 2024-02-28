@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.overtimes.title')</h3>

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                         <tr>
                            <th>ID</th>
                            <td>{{ $overtime->id }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes.fields.pen')</th>
                            <td field-key='pen'>{{ $overtime->pen }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes.fields.name')</th>
                            <td field-key='name'>{{ $overtime->name }}</td>
                        </tr>
                        
                        <tr>
                            <th>@lang('quickadmin.overtimes.fields.designation')</th>
                            <td field-key='designation'>{{ $overtime->designation }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes.fields.form')</th>
                            <td field-key='form'>{{ $overtime->form->session ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.creator')</th>
                            <td field-key='creator'>{{ isset($overtime->form) ? $overtime->form->creator : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.owner')</th>
                            <td field-key='owner'>{{ isset($overtime->form) ? $overtime->form->owner : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.form-no')</th>
                            <td field-key='form_no'>{{ isset($overtime->form) ? $overtime->form->form_no : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.overtime-slot')</th>
                            <td field-key='overtime_slot'>{{ isset($overtime->form) ? $overtime->form->overtime_slot : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.duty-date')</th>
                            <td field-key='duty_date'>{{ isset($overtime->form) ? $overtime->form->duty_date : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.date-from')</th>
                            <td field-key='date_from'>{{ isset($overtime->form) ? $overtime->form->date_from : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.date-to')</th>
                            <td field-key='date_to'>{{ isset($overtime->form) ? $overtime->form->date_to : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes.fields.from')</th>
                            <td field-key='from'>{{ $overtime->from }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes.fields.to')</th>
                            <td field-key='to'>{{ $overtime->to }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes.fields.count')</th>
                            <td field-key='count'>{{ $overtime->count }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.overtimes.fields.worknature')</th>
                            <td field-key='worknature'>{{ $overtime->worknature }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.overtimes.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
