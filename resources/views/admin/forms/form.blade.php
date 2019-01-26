<div v-cloak>
	
	<div class="alert alert-danger" v-if="errors.products_empty" >
	<ul>
        <li v-for="error in errors.products_empty">@{{ error }}</li>
    </ul>
	</div>

	<hr>

	<div class="alert alert-danger" v-if="myerrors.length">
        <ul>
            <li v-for="error in myerrors">@{{ error }}</li>
        </ul>
    </div>

	
	
</div>



<div class="row">
	<div class="col-md-8 form-group">           
	<table class="table">
		<tr>
			<td><label for="session">Session</label></td>
			<td>
			<select name="session" v-model= "form.session" required >
				<option disabled value="">Please select one</option>
				@foreach ($sessions as $session)
					@if ($loop->index == 1)
						<option selected> {{$session}}</option>
					@else
						<option> {{$session}}</option>
					@endif
					
				@endforeach
			</select>
			</td>
		
			<td><label for="duty_date">Date</label></td>
			<td>
			
			<!-- <select name = "duty_date" v-model = "form.duty_date"  @change="onChange" required>
				<option disabled value="">Please select one</option>
				@foreach ($calenderdays as $day)
					<option> {{$day}}   </option>
				@endforeach
			</select> -->

			<date-picker v-model="form.duty_date" @dp-change="onChange"
					:config="configdate"
					placeholder="Select date"
					:required="true"                
					class="form-control">
			</date-picker> 

			</td>
		
			<td><label for="overtime_slot">Overtime claim</label></td>
			<td>
			<select name="overtime_slot" v-model= "form.overtime_slot"  @change="onChangeSlot" required>
			<option disabled value="">Please select one</option>
			<option  v-for="option in slotoptions" > @{{ option }} </option>
		</select>
			</td>
		</tr>
	</table>
	</div>
</div>

<div class="row">
	<div class="col-md-12 form-group ">
		<table class="table">
			<thead>
				<tr>
					<td>No</td>
					<td>PEN - Name</td>
					<td>Designation</td>
					<td>Time-From</td>
					<td>Time-To</td>
					<td>Nature of Work</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row, index) in form.overtimes">
					<td><label type="text" v-text="index+1"></td>

					<td> 
					<multiselect :name="'name[' + index + ']'" v-model= "row.pen"  
					id="'id[' + index + ']'" 
					
					placeholder= "Type to search" 
					:options="pen_names" 
					:searchable="true" 
					:show-labels="false"  
					:close-on-select="true" 
					:allow-empty="false" 
					:options-limit="100" 
					@select="changeSelect" 
					@search-change="asyncFind">
															
					</multiselect></td>

					<td>  <multiselect :name="'desig[' + index + ']'" v-model="row.designation" :allow-empty="false"
							
							:show-labels="false" :options= "muloptions"> </multiselect> </td>

					<td class="col-md-1">
					<!-- <input  :name="'from[' + index + ']'" type="text" v-model="row.from" required> -->
					<date-picker v-model="row.from" :config="configtime"
						:required="true"                
						class="form-control">
					</date-picker> 
					</td>
					<!-- <td class="col-md-1"><input  :name="'to[' + index + ']'"class="form-control" type="text" v-model="row.to" required></td> -->
					<td class="col-md-1">
					<!-- <input  :name="'from[' + index + ']'" type="text" v-model="row.from" required> -->
					<date-picker v-model="row.to" :config="configtime"
						:required="true"                
						>
					</date-picker> 
					</td>
					
					<td class="col-md-1"> <input :name="'remarks[' + index + ']'" class="form-control" type="text" v-model="row.remarks"></td>
					<td><button class="btn btn-danger" @click.prevent="removeElement(index);"><i class="fa fa-trash"></i></button></td>


				</tr>

			</tbody>
			
		</table>

		<div class="col-md-12 form-group">
			<div class="row">
				<button type="button" class="btn btn-success btn-sm" @click.prevent="addRow"><i class="fa fa-plus"></i> Add row</button>
			</div>
        </div>
	</div>
	
</div>
