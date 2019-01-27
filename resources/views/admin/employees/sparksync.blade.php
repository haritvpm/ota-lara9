@extends('layouts.app')

@section('content')
    <h3 class="page-title">Spark Sync</h3>

    @if( $inputview == 0)

    <div class="panel panel-default">
        {!! Form::open(['method' => 'POST', 'route' => ['admin.employees.sparksync']]) !!}

           <div class="panel-body">
            
            <div class="row">

                <div class="col-xs-8 form-group">
                    {!! Form::label('sectt', 'Sectt', ['class' => 'control-label']) !!}
                    {!! Form::textarea('sectt', old('sectt'), ['class' => 'form-control ', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('sectt'))
                        <p class="help-block">
                            {{ $errors->first('sectt') }}
                        </p>
                    @endif
                </div>
             <!--    
                 <div class="col-xs-6 form-group">
                    {!! Form::label('hostel', 'Hostel', ['class' => 'control-label']) !!}
                    {!! Form::textarea('hostel', old('hostel'), ['class' => 'form-control ', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('hostel'))
                        <p class="help-block">
                            {{ $errors->first('hostel') }}
                        </p>
                    @endif
                </div> -->
            </div>
            
        </div>
         <div class="panel-footer">
            <a href="{{route('admin.employees.index')}}" class="btn btn-default">Cancel</a>
              {!! Form::submit('Sync', ['class' => 'btn btn-danger']) !!}
              {!! Form::close() !!}
       
        </div>
    </div>
    @else
     <table  class="table table-condensed table-bordered" style="width:90%">
            
       <tbody>
        @if(count($myerrors))
          
        @foreach ($myerrors as $key => $value)
            <tr  class="danger">
            <td>{{ $key+1 }}</td>
            <td>{{ $value[0] }}</td>
            <td>{{ $value[1] }}</td>
            <td>{{ $value[2] }}</td>
            </tr>
                      
        @endforeach
    
        @endif

        @if(count($added))
        @foreach ($added as $key => $value)
           <tr  class="success">
            <td>{{ $key+1 }}</td>
            <td>{{ $value[0] }}</td>
            <td>{{ $value[1] }}</td>
            <td>{{ $value[2] }}</td>
            </tr>
                      
        @endforeach
     
        @endif

        @if(count($modified))
           
        @foreach ($modified as $key => $value)
            <tr  class="warning">
            <td>{{ $key+1 }}</td>
            <td>{{ $value[0] }}</td>
            <td>{{ $value[1] }}</td>
            <td>{{ $value[2] }}</td>
            </tr>
                      
        @endforeach
      
        @endif

        @if(count($notinpdf))
       
        @foreach ($notinpdf as $key => $value)
            <tr >
            <td>{{ $key+1 }}</td>
            <td>{{ $value[0] }}</td>
            <td>{{ $value[1] }}</td>
            <td>{{ $value[2] }}</td>
            </tr>
                      
        @endforeach
        @endif

        @if(count($ignoreditems))
      
       
        @foreach ($ignoreditems as $key => $value)
        
            <tr  class="info">
            <td>{{ $key+1 }}</td>
            <td>{{ $value[0] }}</td>
            <td>{{ $value[1] }}</td>
            <td>{{ $value[2] }}</td>
            </tr>
                      
        @endforeach
           
         @endif
          </tbody>
        </table>
       
         <hr>
        <a href="{{route('admin.employees.index')}}" class="btn btn-default">Close</a>
    @endif
  

@stop

