@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.forms.title')</h3>

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.forms.fields.session')</th>
                            <td field-key='session'>{{ $form->session }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.creator')</th>
                            <td field-key='creator'>{{ $form->creator }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.owner')</th>
                            <td field-key='owner'>{{ $form->owner }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.form-no')</th>
                            <td field-key='form_no'>{{ $form->form_no }}. (ID = {{ $form->id }})</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.overtime-slot')</th>
                            <td field-key='overtime_slot'>{{ $form->overtime_slot }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.duty-date')</th>
                            <td field-key='duty_date'>{{ $form->duty_date }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.date-from')</th>
                            <td field-key='date_from'>{{ $form->date_from }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms.fields.date-to')</th>
                            <td field-key='date_to'>{{ $form->date_to }}</td>
                        </tr>
                         <tr>
                            <th>Submitted name</th>
                            <td field-key='date_to'>{{ $form->submitted_names }}</td>
                        </tr>
                         <tr>
                            <th>Submitted by</th>
                            <td field-key='date_to'>{{ $form->submitted_by }}</td>
                        </tr>
                         <tr>
                            <th>Submitted_on</th>
                            <td field-key='submitted_on'>{{ $form->submitted_on }}</td>
                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td field-key='remarks'>{{ $form->remarks }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->

<div>
    @can('form_edit')
    
                        <a href="{{ route('admin.forms.edit',[$form->id]) }}" class="btn btn-info">@lang('quickadmin.qa_edit')</a>
    @endcan
</div>

<hr>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="overtimes">
<table class="table table-bordered table-striped {{ count($overtimes) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.overtimes.fields.pen')</th>
            <th>@lang('quickadmin.overtimes.fields.designation')</th>
            <th>From</th>
            <th>To</th>
            <th>@lang('quickadmin.overtimes.fields.count')</th>
            <th>@lang('quickadmin.overtimes.fields.worknature')</th>
            <th>&nbsp;</th>

        </tr>
    </thead>

    <tbody>
        @if (count($overtimes) > 0)
            @foreach ($overtimes as $overtime)
                <tr data-entry-id="{{ $overtime->id }}">
                    <td field-key='pen'>{{ $overtime->pen }}</td>
                    <td field-key='name'>{{ $overtime->name }}</td>
                    <td field-key='designation'>{{ $overtime->designation }}</td>
                    <td field-key='from'>{{ $overtime->from }}</td>
                    <td field-key='to'>{{ $overtime->to }}</td>
                    <td field-key='count'>{{ $overtime->count }}</td>
                    <td field-key='worknature'>{{ $overtime->worknature }}</td>
                                                    <td>
                        @can('overtime_view')
                        <a href="{{ route('admin.overtimes.show',[$overtime->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_view')</a>
                        @endcan
                        @can('overtime_edit')
                        <a href="{{ route('admin.overtimes.edit',[$overtime->id]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_edit')</a>
                        @endcan
                        @can('overtime_delete')
                        {!! Form::open(array(
                            'style' => 'display: inline-block;',
                            'method' => 'DELETE',
                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                            'route' => ['admin.overtimes.destroy', $overtime->id])) !!}
                        {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
                        {!! Form::close() !!}
                        @endcan
                    </td>

                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="12">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.forms.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
