<div v-cloak>
	
	<div class="alert alert-danger" v-if="errors.products_empty" >
	<ul>
        <li v-for="error in errors.products_empty">@{{ error }}</li>
    </ul>
	</div>

	

	<div class="alert alert-warning" v-if="myerrors.length">
        <ul>
            <li v-for="error in myerrors">@{{ error }}</li>
        </ul>
    </div>
	
</div>

<div class="row" v-cloak >
	
	<div class="col-md-4 form-group">           
			<label for="session">Session</label>
			<select {{$readonly}} class ="form-control" name="session" v-model= "form.session" required v-on:change="sessionchanged">
				
				@foreach ($sessions as $session)
				    @php
				    $kla = substr($session,0, strpos($session,'.'));
				    $session_no = substr($session,strpos($session,'.')+1);
				    @endphp
					@if ($loop->index == 1)
						<option value={{$session}} selected>KLA-{{ $kla }}, Session: {{ $session_no }}</option>
					@else
						<option value={{$session}}>KLA-{{ $kla }}, Session: {{ $session_no }}</option>
					@endif
					
				@endforeach
			</select>
	</div>
	<div class="col-md-4 form-group">  
			<label for="duty_date">Date</label>
			 @{{ selectdaylabel }}		
			  
			<date-picker {{$readonly}} v-model="form.duty_date" @dp-hide="onChange"
					:config="configdate"
					placeholder="Select date"
					:required="true"
					           
					class="form-control">
			</date-picker> 
	</div>
	<div class="col-md-4 form-group">  	
			<label for="overtime_slot">OT</label>
			
			<select  class="form-control" name="overtime_slot" v-model= "form.overtime_slot"  @change="onChangeSlot" required>
			<!-- <option disabled value=""></option> -->
			<option  v-for="option in slotoptions" > @{{ option }} </option>
		</select>
	</div>	
	
</div>
<hr>
<div class="row" v-cloak>
	
	<div class="col-md-12 form-group ">
		<table class="table  table-condensed">
			<thead v-show="form.overtimes.length" >
				<tr style="font-size: 12px; font-weight: bold">
					<th>No</th>
					<th>PEN - Name</th>
					<th>Designation</th>
					<th>PunchIn</th>
					<th>PunchOut</th>
					<th>Time-From</th>
					<th>Time-To</th>
					<!-- <th>Work-Nature</th> -->
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row, index) in form.overtimes" :class="{ info: index%2 }">
					<td style="width:1px;"> <small> 
<button  class="btn btn-default" data-toggle="tooltip" title="Insert row" @click.prevent="insertElement(index+1);"> <span v-text="index+1"></span> </small> </button></td>
					
					<td>
					<multiselect :name="'name[' + index + ']'" v-model= "row.pen" :id=index  :ref="'field-'+index"  placeholder= "Type to search" 
					:options="pen_names" 
					:searchable="true" 
					:show-labels="false"  
					:close-on-select="true" 
					:allow-empty="false" 
					:options-limit="100" 
					:tabindex="3" 
					@select="changeSelect" 
											
					@search-change="asyncFind">
					<span slot="noResult"></span>										
					</multiselect>
					</td>

					<!-- <td><multiselect :name="'desig[' + index + ']'" v-model="row.designation" :allow-empty="false"
							 :tabindex="4" 
							:show-labels="false" :options= "muloptions" :disabled="true"> 
						<span slot="noResult"></span>		
					</multiselect>
					</td> -->
					<td>
						
					<div class="small"> @{{ row.designation }}</div> 
					</td>
					<td class="col-md-1">
					<input  :name="'punchin[' + index + ']'" type="text" v-model="row.punchin" required class="form-control" >
					<!-- <date-picker v-model="row.from" :config="configtime"
						:required="true"                
						class="form-control">
					</date-picker> --> 
					</td>
					<td class="col-md-1">
					<input  :name="'punchout[' + index + ']'" type="text" v-model="row.punchout" required class="form-control" >
					<!-- <date-picker v-model="row.from" :config="configtime"
						:required="true"                
						class="form-control">
					</date-picker> --> 
					</td>
					<td class="col-md-1">
					<input  :name="'from[' + index + ']'" type="text" v-model="row.from" required class="form-control" >
					<!-- <date-picker v-model="row.from" :config="configtime"
						:required="true"                
						class="form-control">
					</date-picker> --> 
					</td>
					
					<td class="col-md-1">
					<input  :name="'to[' + index + ']'" type="text" v-model="row.to" required class="form-control">
					<!-- <date-picker v-model="row.to" :config="configtime"
						:required="true"                
						>
					</date-picker>  -->
					</td>
					
					<!-- <td class="col-md-2"> <input :name="'worknature[' + index + ']'" class="form-control" type="text" v-model="row.worknature" maxlength="180"  required></td> -->
					<td style="width:1px;"><button class="btn btn-danger"  @click.prevent="removeElement(index);"   data-toggle="tooltip" title="remove row"><i class="fa fa-times"></i></button>
					

					</td>
					


				</tr>

			</tbody>
			
		</table>

		<div class="col-md-12 form-group">
			<div class="row">
				<button type="button" class="btn btn-success btn-sm" @click.prevent="addRow"><i class="fa fa-plus"></i> Add row</button>

				
				<!-- <a href="#" class="pull-right" v-show ="form.overtimes.length>1" @click="copyworknaturedown" >WorkNature</a> -->
				<span v-show ="form.overtimes.length>1" class="pull-right">&nbsp;|&nbsp;</span>
				 <a href="#" class="pull-right" v-show ="form.overtimes.length>1" @click="copytimedown" >Time</a>

				 <span v-show ="form.overtimes.length>1" class="pull-right">Copy from First Row:&nbsp;</span>

				 <span v-show ="form.overtimes.length>1" class="pull-right">Copy Time to Next Row: F4 | &nbsp;</span>

			</div>
        </div>

    	
        <div class="row">
		<div class="col-md-12 form-group">
        		<label for="comment">Nature of work done: </label>
        		<textarea class="form-control" rows="1" v-model="form.worknature" maxlength="65000" placeholder="" required></textarea>
        	</div>
        	<div class="col-md-12 form-group">
        		<label for="comment">Remarks, if any: <small>(max 190 chars)</small></label>
        		<textarea class="form-control" rows="1" v-model="form.remarks" maxlength="190" placeholder=""></textarea>
        	</div>
        </div>	    

	</div>

	
</div>
