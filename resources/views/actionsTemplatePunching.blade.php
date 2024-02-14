
@if(str_contains($row->punching, 'AEBAS') )
<a href="{{ route($routeKey.'.fetch', $row->date ) }}"  class="btn btn-xs btn-danger">Fetch</a>
<a href="{{ route($routeKey.'.index', ['datefilter' => $row->date]) }}" class="btn btn-xs btn-success">@lang('quickadmin.qa_view')</a>
@endif
