
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
	

</div>
<hr>
<div class="row" v-cloak>
	<div class="col-md-12 form-group ">
		<table class="table">
			<thead v-show="form.exemptions.length" >
				<tr>
					<td>No</td>
					<td>PEN - Name</td>
					<td>Designation</td>
									
					<td>Reason</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row, index) in form.exemptions">
					<td style="width:1px;"> <small> 
<button  class="btn btn-default" data-toggle="tooltip" title="Insert row" @click.prevent="insertElement(index+1);"> <span v-text="index+1"></span> </small> </button></td>
					<td>
										
					<multiselect :name="'name[' + index + ']'" v-model= "row.pen" id="ajax"  :ref="'field-'+index" placeholder= "Type to search" 
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

					<td><multiselect :name="'desig[' + index + ']'" v-model="row.designation" :allow-empty="false"
							
							:show-labels="false" :options= "muloptions"> 
						<span slot="noResult"></span>		
					</multiselect>
					</td>

					<td class="col-md-3"> <input :name="'worknature[' + index + ']'" class="form-control" type="text" v-model="row.worknature" required></td>
					<td style="width:1px;"><button class="btn btn-danger"  @click.prevent="removeElement(index);"><i class="fa fa-times"></i></button>
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
