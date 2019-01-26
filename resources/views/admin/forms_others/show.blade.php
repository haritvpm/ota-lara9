@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.forms-others.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.forms-others.fields.session')</th>
                            <td field-key='session'>{{ $forms_other->session }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms-others.fields.creator')</th>
                            <td field-key='creator'>{{ $forms_other->creator }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms-others.fields.owner')</th>
                            <td field-key='owner'>{{ $forms_other->owner }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms-others.fields.form-no')</th>
                            <td field-key='form_no'>{{ $forms_other->form_no }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms-others.fields.overtime-slot')</th>
                            <td field-key='overtime_slot'>{{ $forms_other->overtime_slot }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms-others.fields.duty-date')</th>
                            <td field-key='duty_date'>{{ $forms_other->duty_date }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms-others.fields.date-from')</th>
                            <td field-key='date_from'>{{ $forms_other->date_from }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.forms-others.fields.date-to')</th>
                            <td field-key='date_to'>{{ $forms_other->date_to }}</td>
                        </tr>
                    </table>
                </div>

          </div><!-- Nav tabs -->
          <div>
    @can('form_edit')
    
                        <a href="{{ route('admin.forms_others.edit',[$forms_other->id]) }}" class="btn btn-info">@lang('quickadmin.qa_edit')</a>
    @endcan
</div>

<hr>
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#overtimesothers" aria-controls="overtimesothers" role="tab" data-toggle="tab">Overtimes Others</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="overtimesothers">
<table class="table table-bordered table-striped {{ count($overtimes_others) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.overtimes-others.fields.pen')</th>
                        <th>@lang('quickadmin.overtimes-others.fields.designation')</th>
                                                
                        <th>@lang('quickadmin.overtimes-others.fields.from')</th>
                        <th>@lang('quickadmin.overtimes-others.fields.to')</th>
                        <th>@lang('quickadmin.overtimes-others.fields.count')</th>
                        <th>Rate</th>
                        <th>@lang('quickadmin.overtimes-others.fields.worknature')</th>
                                                <th>&nbsp;</th>

        </tr>
    </thead>

    <tbody>
        @if (count($overtimes_others) > 0)
            @foreach ($overtimes_others as $overtimes_other)
                <tr data-entry-id="{{ $overtimes_other->id }}">
                    <td field-key='pen'>{{ $overtimes_other->pen }}</td>
                                <td field-key='designation'>{{ $overtimes_other->designation }}</td>
                                
                                <td field-key='from'>{{ $overtimes_other->from }}</td>
                                <td field-key='to'>{{ $overtimes_other->to }}</td>
                                <td field-key='count'>{{ $overtimes_other->count }}</td>
                                <td field-key='rate'>{{ $overtimes_other->rate }}</td>
                                <td field-key='worknature'>{{ $overtimes_other->worknature }}</td>

                                                                <td>
                                    @can('overtimes_other_view')
                                    <a href="{{ route('admin.overtimes_others.show',[$overtimes_other->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('overtimes_other_edit')
                                    <a href="{{ route('admin.overtimes_others.edit',[$overtimes_other->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('overtimes_other_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.overtimes_others.destroy', $overtimes_other->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
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

            <a href="{{ route('admin.forms_others.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
