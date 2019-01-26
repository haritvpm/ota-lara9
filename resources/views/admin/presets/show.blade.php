@extends('layouts.app')

@section('content')
    <h3 class="page-title">Presets</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>


        <div class="panel-body table-responsive">

             <div >
                @if(Auth::user()->isAdmin())   
               
                    User : 
                    {{ $preset->user->Title or '' }} <br>
               
                @endif
               Preset Name : 
                {{ $preset->name }}  <br><br>
              
                    
                
            </div>
            <div >
                <div >
                    <table class="table table-striped table-condensed ">
                        
                         @foreach ($loadedpreset as $key => $val) 
                        <tr>
                            <td>{{  $loop->iteration }}. </td>                          
                            <td>
                                <!-- {!! $preset->pens !!} -->
                           
                                {{ $key }}
                            
                            </td>
                            <td>{{ $val }}</td>
                           

                        </tr>
                         @endforeach
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.presets.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
