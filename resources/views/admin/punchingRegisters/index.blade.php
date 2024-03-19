@extends('layouts.app')
@section('content')

<div class="" id="app">
 

    <div class="row">
        <div class="col-md-4 form-group">           
                <label for="section">Section/Officer</label>
                <select class ="form-control" name="section" v-model= "section" required v-on:change="sectionchanged">
                <option value='*'> All </option>
                    @foreach ($section_employees as $section)
                                
                            <option value={{$section->id}}> {{$section->section_or_offfice->name}} </option>
                                            
                    @endforeach
                </select>
        </div>

        <div class="col-md-4 form-group">  
                <label for="duty_date">Date</label>
                           
                <date-picker v-model="date" @dp-change="onChange"
                        :config="configdate"
                        placeholder="Select date"
                        :required="true"
                                
                        class="form-control">
                </date-picker> 
        </div>

    </div>

    <div class="">
        <div class="">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.date') }}
                        </th>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.employee') }}
                        </th>
                        <th>
                            {{ trans('cruds.employee.fields.pen') }}
                        </th>
                        <th>
                            {{ trans('cruds.employee.fields.aadhaarid') }}
                        </th>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.punchin') }}
                        </th>
                        <th>
                            {{ trans('cruds.punching.fields.in_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.punching.fields.out_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.punching.fields.at_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.duration') }}
                        </th>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.flexi') }}
                        </th>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.grace_min') }}
                        </th>
                        <th>
                            {{ trans('cruds.punchingRegister.fields.extra_min') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                  
                     
                        <tr v-for="(row, index) in section_employees" >
                            <td>
                            @{{ row.section_or_offfice.name }}
                            </td>
                            <td>
                            @{{ row.employee.name }}
                            </td>
                            <td>
                              
                            </td>
                            <td>
                              
                            </td>
                            <td>
                               
                            </td>
                            <td>
                              
                            </td>
                            <td>
                              
                            </td>
                            <td>
                            
                            </td>
                            <td>
                            
                            </td>
                            <td>
                              
                            </td>
                            <td>
                               
                            </td>
                            <td>
                              
                            </td>
                            <td>
                              
                            </td>
                            <td>
                               


                            </td>

                        </tr>
               
                </tbody>
            </table>
        </div>
    </div>
</div>



@stop
@section('javascript')
@parent
<script>

Vue.use(VueSweetAlert.default)
    Vue.component('date-picker', VueBootstrapDatetimePicker.default);

</script>

<script type="text/javascript" src="{{ URL::asset('js/punching-register.js') }}"></script>

@stop