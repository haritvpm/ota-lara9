
<body>



<h5 class="page-title" style="text-align: center">
    SECRETARIAT OF THE KERALA LEGISLATURE
    
</h5>
<h6 class="page-title" style="text-align: center">
    OTA STATEMENT FOR OTHER DEPARTMENT
    
</h6>


<div>
    <div class="panel-heading">
        
     <p align="right" style="font-size:10px;"> No.{{ $form->id }}, Updated: 
        {{ date('d-m-Y', strtotime($form->updated_at)) }}, Printed: {{$printdate}} {{$form->MD5}}</p>

    
    <div class="panel-body ">
        <div class="row">
            <div class="col-md-12">
                <table  style="font-size:14px;">
                    <tr>
                        <td>
                       <strong>{!! html_entity_decode($romankla) !!}</strong> KLA,
                       <strong>{!! html_entity_decode( $sessionnumber) !!}</strong> Session
                       
                       </td>
                    </tr>

                    <tr>
                       
                        <td>Section/Office:  {{ $form->created_by->displayname }}  {{ $form->created_by->name }}
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    

                        @if($form->overtime_slot == 'Sittings')
                       
                        Period:  From {{$form->date_from}} to {{$form->date_to}}
                        @else
                           
                        @lang('quickadmin.forms.fields.duty-date'):  {{ $form->duty_date }} ({{ $descriptionofday  or $daytype}})
                        
                        @endif
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                  
                                               
                        @lang('quickadmin.forms.fields.overtime-slot'):  {{ $form->overtime_slot }}</td>   

                        

                    </tr>

                                                  

                    
                    

                </table>
            </div>
            
        </div><!-- Nav tabs -->

        <br>
               
        <table class="table2" cellpadding="4" align="center">
      
            <thead style="font-size:14px;">
                <tr>
                    <th>No.</th>
                    <th >&nbsp;Name</th>
                    <th style="text-align:center;">&nbsp;PEN</th>
                    <th >&nbsp;Desig</th>
                    <!-- <th>@lang('quickadmin.overtimes.fields.designation')</th> -->
                    @if($form->overtime_slot == 'Sittings')
                    <th style="text-align:center;">&nbsp;Period from</th>
                    <th style="text-align:center;">&nbsp;Period to</th>
                    <th >&nbsp;Sittings&nbsp;</th>
                    
                    @else
                    <th style="text-align:center;">@lang('quickadmin.overtimes.fields.from')</th>
                    <th style="text-align:center;">@lang('quickadmin.overtimes.fields.to')</th>
                    <th style="text-align:center;"> <small>Nature of Work</small></th>
                    
                    @endif
                    <th style="text-align:center;">&nbsp;IFSC</th>
                    <th >&nbsp;&nbsp;Account</th>
                    <th >&nbsp;Mob</th>


                </tr>
            </thead>

            <tbody style="font-size:12px;">
                @if (count($overtimes) > 0)
                @foreach ($overtimes as $overtime)
                <?php
                $pen_actual =   substr( $overtime->pen,0, strpos( $overtime->pen,'-' ) );
                $name =   substr( $overtime->pen,strpos( $overtime->pen,'-' )+1 );
                $desig =  substr( $name,strpos( $name,',' )+1);
                $name =   substr( $name,0, strpos( $name,',' ) );

                ?>
                <tr data-entry-id="{{ $overtime->id }}">

                    <td style="width:1px;"> <small> {{  $loop->iteration }}</small></td>
                   
                    <td nowrap><small>{{$name}}</small></td>
                    <td style="text-align:center;"><small>{{$pen_actual}}</small></td>
                    <td ><small>{{$desig}}</small></td>
                    

                  @if($form->overtime_slot == 'Sittings')
                    <td nowrap style="text-align:center;"><small>{{ $overtime->from }}</small></td>
                    <td nowrap style="text-align:center;"><small>{{ $overtime->to }}</small></td>
                    <td  style="width:1px;text-align:center;">{{ $overtime->count }}</td>
                @else
                    <td nowrap style="text-align:center;">{{ date("h:i a", strtotime($overtime->from)) }}</td>
                    <td nowrap style="text-align:center;">{{ date("h:i a", strtotime($overtime->to)) }}</td>
                    <td style="text-align:center;"><small>{{ $overtime->worknature }}</small></td>
                @endif
                  
                  
                    <td class="monospacefont"   style="text-align:center;"> {{ optional($overtime->employeesother)->ifsc }}  </td>
                      <td class="monospacefont"> &nbsp;{{ optional($overtime->employeesother)->account_no }} </td>
                    <td > <small>{{ optional($overtime->employeesother)->mobile }} </small>  </td>


                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="12">@lang('quickadmin.qa_no_entries_in_table')</td>
                </tr>
                @endif
            </tbody>
        </table>

        <br>

        <!-- <div style="font-size:14px;" class="row">
            <div class="col-md-12 form-group">
                
                <p ><strong>Remarks</strong> : {{ $form->remarks }} </p>
            </div>
        </div>    -->   

    </div>


    
    
    <div align="right" style="font-size:9px;"><br><br><br><br>Signature, Name and Designation<br>
        of the Officer forwarding the statement</div>

    <div style="font-size:9px;">Countersigned by</div>

</div>

</div>
</body>
