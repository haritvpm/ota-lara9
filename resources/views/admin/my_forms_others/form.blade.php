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
					@if ($loop->index == 1)
						<option selected> {{$session}}</option>
					@else
						<option > {{$session}}</option>
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
			<label for="overtime_slot">Overtime claim</label>
			
			<select {{$readonly}}  class="form-control" name="overtime_slot" v-model= "form.overtime_slot"  @change="onChangeSlot" required>
			<option disabled value="">Please select one</option>
			<option  v-for="option in slotoptions" > @{{ option }} </option>
		</select>
	</div>		
	

</div>
<hr>
<div class="row" v-cloak>
	<div class="col-md-12 form-group ">
		<table class="table table-condensed">
			<thead v-show="form.overtimes.length" >
				<tr style="font-size: 12px; font-weight: bold">
					<!-- <th></th> -->
					<th>No</th>
					<th>PEN - Name, Designation</th>
					<!-- <td>Designation</td> -->
					<th>Time-From</th>
					<th>Time-To</th>
					<th>Work-Nature</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row, index) in form.overtimes">
					<!-- <td style="width:1px;"><input type="checkbox" id="checkbox" v-model="row.checked"></td> -->
					<td style="width:1px;"><small> 
               		 <span v-text="index+1"></span> </small>
            		</td>

					<td class="col-md-6"> 
					<multiselect :name="'name[' + index + ']'" v-model= "row.pen" id="ajax" :ref="'field-'+index" placeholder= "Type to search" 
					:options="pen_names" 
					:searchable="true" 
					:show-labels="false"  
					:close-on-select="true" 
					:allow-empty="false" 
					:options-limit="100" 
					@select="changeSelect"
												
					@search-change="asyncFind">
						<span slot="noResult"></span>									
					</multiselect></td>

					<!-- <td>  <multiselect :name="'desig[' + index + ']'" v-model="row.designation" :allow-empty="false"
							
							:show-labels="false" :options= "muloptions"> </multiselect> </td> -->

					<td class="col-md-2">
					<input  :name="'from[' + index + ']'" type="text" v-model="row.from" required class="form-control">
					<!-- <date-picker v-model="row.from" :config="configtime"
						:required="true"                
						class="form-control">
					</date-picker> --> 
					</td>
					
					<td class="col-md-2">
					<input  :name="'to[' + index + ']'" type="text" v-model="row.to" required class="form-control">
					<!-- <date-picker v-model="row.to" :config="configtime"
						:required="true"                
						>
					</date-picker>  -->
					</td>
					
					<td class="col-md-2"> <input :name="'worknature[' + index + ']'" class="form-control" type="text" v-model="row.worknature"></td>
					<td style="width:1px;"><button class="btn btn-danger" @click.prevent="removeElement(index);"><i class="fa fa-times"></i></button></td>


				</tr>

			</tbody>
			
		</table>

		<div class="col-md-12 form-group">
			<div class="row">
				<button type="button" class="btn btn-success btn-sm" @click.prevent="addRow"><i class="fa fa-plus"></i> Add row</button>


				<a href="#" class="pull-right" v-show ="form.overtimes.length>1" @click="copyworknaturedown" >WorkNature</a>
				<span v-show ="form.overtimes.length>1" class="pull-right">&nbsp;|&nbsp;</span>
				 <a href="#" class="pull-right" v-show ="form.overtimes.length>1" @click="copytimedown" >Time</a>

				 <span v-show ="form.overtimes.length>1" class="pull-right">Copy from First Row:&nbsp;</span>
				


			</div>
        </div>

    	
        <div class="row">
        	<div class="col-md-12 form-group">
        		<label for="comment">Remarks:</label>
        		<textarea class="form-control" rows="1" v-model="form.remarks" maxlength="190"></textarea>
        	</div>
        </div>	    
 
	</div>
	
</div>
