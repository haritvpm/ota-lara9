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
	<input type="hidden" name="overtime_slot"  type="text" v-model= "form.overtime_slot"  class="form-control"  value="multi">

</div>
<hr>
<div class="row" v-cloak>
	
	<div class="col-md-12 form-group ">
		<table class="table  table-condensed">
			<thead v-show="form.overtimes.length" >
			
				<tr class="text-center" style="font-size: 12px; font-weight: bold">
					<th>No</th>
					<th>PEN - Name</th>
					<th>Designation</th>
					
					<th v-show="dayHasPunching">PunchIn <-> PunchOut</th>
					
					<th>Time-From <-> Time-To</th>
					<th class="text-center">OT</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row, index) in form.overtimes" :class="{ info: index%2 }" >
					<td class="text-center align-middle" style="width:1px;"> <small> 
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

					<td class="align-middle">
					<input class="form-control" style="font-size: 12px;" :value="row.designation" readonly >
					</td>
					
					
					<td v-show="dayHasPunching" class="col-md-2">
					<div class="input-group">
					<input  :name="'punchin[' + index + ']'" type="text" v-model="row.punchin" required class="form-control" :disabled="!dayHasPunching || !row.punching" :readonly="!allowPunchingEntry || row.punchin_from_aebas" autocomplete="off">
					
					<input  :name="'punchout[' + index + ']'" type="text" v-model="row.punchout" required class="form-control" :disabled="!dayHasPunching || !row.punching" :readonly="!allowPunchingEntry || row.punchout_from_aebas"  autocomplete="off">
					</div>
					</td>
				

					<td class="col-md-2">
					<div class="input-group">
					<input  :name="'from[' + index + ']'" type="text" v-model="row.from" required class="form-control"  autocomplete="off">
					<input  :name="'to[' + index + ']'" type="text" v-model="row.to" required class="form-control"  autocomplete="off">
					</div>
					</td>
					
					<td class="text-center align-middle">
					
					<div class="form-check form-check-inline">
					<input class="form-check-input checkbox-1x"  v-if="slotoptions.includes('First')" type="checkbox"   :id="'Firstslot[' + index + ']'" value="First" v-model="row.slots">
					<label class="form-check-label" v-if="slotoptions.includes('First')" :for="'Firstslot[' + index + ']'" >
					<div v-html="firstOTLabel"></div>
					</label>
					</div>
					<div class="form-check form-check-inline">
					<input class="form-check-input checkbox-1x" v-if="slotoptions.includes('Second')" type="checkbox"   :id="'Secondslot[' + index + ']'" value="Second" v-model="row.slots">
					<label class="form-check-label" v-if="slotoptions.includes('Second')" :for="'Secondslot[' + index + ']'">2<sup>nd</sup></label>
					</div>
					<div class="form-check form-check-inline">
					<input class="form-check-input checkbox-1x" v-if="slotoptions.includes('Third') && !canShowAddlOT(row)" type="checkbox"   :id="'Thirdslot[' + index + ']'" value="Third" v-model="row.slots">
					<label class="form-check-label" v-if="slotoptions.includes('Third') && !canShowAddlOT(row)" :for="'Thirdslot[' + index + ']'">3<sup>rd</sup></label>
					</div>
					<div class="form-check form-check-inline">
					<input class="form-check-input checkbox-1x" v-if="slotoptions.includes('Additional') && canShowAddlOT(row)" type="checkbox"   :id="'Addlslot[' + index + ']'" value="Addl" v-model="row.slots">
					<label class="form-check-label"  v-if="slotoptions.includes('Additional') && canShowAddlOT(row)" :for="'Addlslot[' + index + ']'">Addl</label>
					</div>
				
					</td>
					
					
					<td class="text-center align-middle" style="width:1px;">  <button class="btn btn-danger"  @click.prevent="removeElement(index);" ><i class="fa fa-times"></i></button>
					

					</td>
					


				</tr>

			</tbody>
			
		</table>

		<div class="col-md-12 form-group">
			<div class=" clearfix">
				<button type="button" class="btn btn-success btn-sm" @click.prevent="addRow"><i class="fa fa-plus"></i> Add row</button>
				<!-- <button type="button" class="btn btn-primary btn-sm" @click.prevent="fetchPunching"><i class="fa fa-clock-o"></i> Get Punching Times</button> -->

				
				<!-- <a href="#" class="float-right" v-show ="form.overtimes.length>1" @click="copyworknaturedown" >WorkNature</a> -->
				<!-- <span v-show ="form.overtimes.length>1" class="float-right">&nbsp;|&nbsp;</span> -->
				 <a href="#" class="pull-right" v-show ="form.overtimes.length>1" @click="copytimedown" >Time</a>

				 <span v-show ="form.overtimes.length>1" class="float-right">Copy from First Row:&nbsp;</span>

				 <!-- <span v-show ="form.overtimes.length>1" class="float-right">Copy Time to Next Row: F4 | &nbsp;</span> -->

			</div>
        </div>

    	
        <div class="row">
		<div class="col-md-12 form-group">
        		<label for="comment">Nature of work done: </label>
        		<input class="form-control form-control-sm" rows="1" v-model="form.worknature" maxlength="65000" placeholder="" required></input>
        	</div>
        	<div class="col-md-12 form-group">
        		<label for="comment">Remarks, if any: <small>(max 190 chars)</small></label>
        		<input class="form-control form-control-sm" rows="1" v-model="form.remarks" maxlength="190" placeholder=""></input>
        	</div>
        </div>	    

	</div>

	
</div>
