
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
			<select {{$readonly}} 	tabindex="0"  class ="form-control" name="session" v-model= "form.session" required v-on:change="sessionchanged">
				
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
		<label for="duty_date">Date From</label>
					
		<date-picker readonly v-model="form.date_from" @dp-change="onChange"
				:config="configdate"
				placeholder="Select date"
				:required="true"                
				class="form-control">
		</date-picker> 
	</div>
	<div class="col-md-4 form-group">  	
		<label for="duty_date">Date To</label>
				
		<date-picker readonly v-model="form.date_to" @dp-change="onChange"
				:config="configdate"
				placeholder="Select date"
				:required="true"                
				class="form-control">
		</date-picker> 
		</select>
	</div>		
	

</div>
<hr>
<div class="row" v-cloak>
	<div class="col-md-12 form-group ">
		<table class="table table-condensed ">
			<thead v-show="form.overtimes.length" >
				<tr style="font-size: 12px; font-weight: bold">
					<th>No</th>
					<th>PEN - Name, Designation</th>
					<!-- <td>Designation</td> -->
					<th>Period-From</th>
					<th>Period-To</th>
					<th>Sitting days attended</th>
					<!-- <th>Leave/ Transfer</th> -->
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row, index) in form.overtimes">
					<td  style="width:1px;"><small> 
                <span v-text="index+1"></span> </small></td>

					<td class="col-md-6"> 
					<multiselect :name="'name[' + index + ']'" v-model= "row.pen" :ref="'field-'+index" id="ajax" placeholder= "Type to search" 
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
					<!-- <input  :name="'from[' + index + ']'" type="text" v-model="row.from" required> -->
					<date-picker v-model="row.from" :config="configdate"
						:required="true"
						placeholder="Period from which statement is related"
						class="form-control">
					</date-picker> 
					</td>
					<!-- <td class="col-md-1"><input  :name="'to[' + index + ']'"class="form-control" type="text" v-model="row.to" required></td> -->
					<td class="col-md-2">
					<!-- <input  :name="'from[' + index + ']'" type="text" v-model="row.from" required> -->
					<date-picker v-model="row.to" :config="configdate"
						:required="true"
						placeholder="Period to which statement is related"                
						class="form-control">
					</date-picker> 
					</td>
					<td class="col-md-2"> <input type="number"  :name="'count[' + index + ']'" class="form-control" type="text" min=1 oninput="validity.valid||(value='');"  v-model="row.count"></td>
					<!-- <td class="col-md-1"> <input :name="'worknature[' + index + ']'" class="form-control" type="text" v-model="row.worknature"></td> -->
					<td  style="width:1px;"><button class="btn btn-danger" @click.prevent="removeElement(index);"><i class="fa fa-times"></i></button></td>


				</tr>

			</tbody>
			
		</table>

		<div class="col-md-12 form-group">
			<div class="row">
				<button type="button" class="btn btn-success btn-sm" @click.prevent="addRow"><i class="fa fa-plus"></i> Add row</button>
			</div>
        </div>

        <div class="row">
        	<div class="col-md-12 form-group">
        		<label for="comment">Remarks:</label>
        		<textarea class="form-control" rows="2" v-model="form.remarks" maxlength="190"></textarea>
        	</div>
        </div>	    

	</div>
	
</div>
