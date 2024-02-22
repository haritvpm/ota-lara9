

@can($gateKey.'view')
    <a href="{{ route($routeKey.'.show', $row->id ) }}"
       class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
@endcan
@can($gateKey.'edit')
    <a href="{{ route($routeKey.'.edit', $row->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
@endcan
@can($gateKey.'delete')
    {!! Form::open(array(
        'style' => 'display: inline-block;',
        'method' => 'DELETE',
        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
        'route' => [$routeKey.'.destroy', $row->id])) !!}
    {!! Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm'] ) !!}


    {!! Form::close() !!}
@endcan