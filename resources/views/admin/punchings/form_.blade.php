<div >
	
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

<div class="row">
	
	<div class="col-md-4 form-group">           
			<label for="session">Session</label>
			<select  class ="form-control" name="session" v-model= "form.session" required v-on:change="sessionchanged">
				
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
			<label for="pen">PEN</label>
			 		  
			  <!-- <multiselect :name="pen" v-model= "form.pen" 
			 		placeholder= "Type to search" 
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
					</multiselect>  -->
			
					<multiselect 
			 		placeholder= "Type to search" name="pen"  v-model= "form.pen" 
					 :options="pen_names" :searchable="true"  :show-labels="false"  
					:close-on-select="true" 
					:allow-empty="false" 
					:options-limit="100" 
					:tabindex="3" 
					@search-change="asyncFind"
					@select="changeSelect" 
					id="ajax"  
					>
					<span slot="noResult"></span>										
					</multiselect> 
	</div>
		
</div>
<hr>
<div class="row" >
	
	<div class="col-md-12 form-group ">
		<table class="table  table-condensed">
			<thead v-show="form.punchings.length" >
				<tr style="font-size: 12px; font-weight: bold">
					<th>No</th>
					<th>Date</th>
					
					<th>Time-From</th>
					<th>Time-To</th>
					
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row, index) in form.punchings" :class="{ info: index%2 }">
					<td style="width:1px;"> <small> 
<button  class="btn btn-default" data-toggle="tooltip" title="Insert row" @click.prevent="insertElement(index+1);"> <span v-text="index+1"></span> </small> </button>
					</td>
					<td>
						
					<date-picker :name="'date[' + index + ']'" v-model= "row.date" @dp-hide="onChange"
					:config="configdate"
					placeholder="Select date"
					:required="true"
					class="form-control">
			</date-picker> 
					
					<td class="col-md-1">
					<input  :name="'punch_in[' + index + ']'" type="text" v-model="row.punch_in" required class="form-control" >
					
					</td>
					
					<td class="col-md-1">
					<input  :name="'punch_out[' + index + ']'" type="text" v-model="row.punch_out" required class="form-control">
				
					</td>
					
	
					<td style="width:1px;"><button class="btn btn-danger"  @click.prevent="removeElement(index);"   data-toggle="tooltip" title="remove row"><i class="fa fa-times"></i></button>
					
					</td>
					


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
        		<label for="comment">Remarks, if any: <small>(max 190 chars)</small></label>
        		<textarea class="form-control" rows="1" v-model="form.remarks" maxlength="190" placeholder=""></textarea>
        	</div>
        </div>	    

	</div>
	
</div>
