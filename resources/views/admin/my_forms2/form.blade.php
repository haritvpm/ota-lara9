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
			  
			<date-picker {{$readonly}} v-model="form.duty_date" @dp-change="onChange"
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
				<tr style="font-size: 12px; font-weight: bold">
					<th>No</th>
					<th>PEN - Name</th>
					<th>Designation</th>
					
					<th v-show="dayHasPunching">PunchIn</th>
					<th v-show="dayHasPunching">PunchOut</th>
					
					<th>Time-From</th>
					<th>Time-To</th>
					<th class="text-center">OT</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row, index) in form.overtimes" :class="{ info: index%2 }" >
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

					<td>
					<div class="small"> @{{ row.designation }} </div> 
					</td>
					
					
					<td v-show="dayHasPunching" class="col-md-1">
					<input  :name="'punchin[' + index + ']'" type="text" v-model="row.punchin" required class="form-control" :disabled="!dayHasPunching || !row.punching" :readonly="!allowPunchingEntry" autocomplete="off">
					</td>
					
					<td v-show="dayHasPunching" class="col-md-1">
					<input  :name="'punchout[' + index + ']'" type="text" v-model="row.punchout" required class="form-control" :disabled="!dayHasPunching || !row.punching" :readonly="!allowPunchingEntry"  autocomplete="off">
					</td>
				

					<td class="col-md-1">
					<input  :name="'from[' + index + ']'" type="text" v-model="row.from" required class="form-control"  autocomplete="off">
					</td>
					
					<td class="col-md-1">
					<input  :name="'to[' + index + ']'" type="text" v-model="row.to" required class="form-control"  autocomplete="off">
					</td>
					
					<td  class="col-md-2">
					
					
					<input v-if="slotoptions.includes('First')" type="checkbox"   :id="'Firstslot[' + index + ']'" value="First" v-model="row.slots">
					<label v-if="slotoptions.includes('First')" :for="'Firstslot[' + index + ']'" >
					<div v-html="firstOTLabel"></div>
					</label>

					<input v-if="slotoptions.includes('Second')" type="checkbox"   :id="'Secondslot[' + index + ']'" value="Second" v-model="row.slots">
					<label v-if="slotoptions.includes('Second')" :for="'Secondslot[' + index + ']'">2<sup>nd</sup></label>

					<input v-if="slotoptions.includes('Third') && !canShowAddlOT(row)" type="checkbox"   :id="'Thirdslot[' + index + ']'" value="Third" v-model="row.slots">
					<label v-if="slotoptions.includes('Third') && !canShowAddlOT(row)" :for="'Thirdslot[' + index + ']'">3<sup>rd</sup></label>

					<input v-if="slotoptions.includes('Additional') && canShowAddlOT(row)" type="checkbox"   :id="'Addlslot[' + index + ']'" value="Addl" v-model="row.slots">
					<label v-if="slotoptions.includes('Additional') && canShowAddlOT(row)" :for="'Addlslot[' + index + ']'">Addl</label>
 
				
					</td>
					
					
					<td style="width:1px;">  <button class="btn btn-danger"  @click.prevent="removeElement(index);" ><i class="fa fa-times"></i></button>
					

					</td>
					


				</tr>

			</tbody>
			
		</table>

		<div class="col-md-12 form-group">
			<div class="row">
				<button type="button" class="btn btn-success btn-sm" @click.prevent="addRow"><i class="fa fa-plus"></i> Add row</button>
				<!-- <button type="button" class="btn btn-primary btn-sm" @click.prevent="fetchPunching"><i class="fa fa-clock-o"></i> Get Punching Times</button> -->

				
				<!-- <a href="#" class="pull-right" v-show ="form.overtimes.length>1" @click="copyworknaturedown" >WorkNature</a> -->
				<span v-show ="form.overtimes.length>1" class="pull-right">&nbsp;|&nbsp;</span>
				 <a href="#" class="pull-right" v-show ="form.overtimes.length>1" @click="copytimedown" >Time</a>

				 <span v-show ="form.overtimes.length>1" class="pull-right">Copy from First Row:&nbsp;</span>

				 <!-- <span v-show ="form.overtimes.length>1" class="pull-right">Copy Time to Next Row: F4 | &nbsp;</span> -->

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
