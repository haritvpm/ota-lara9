

<my-modal></my-modal>
@include('admin.my_forms2.punchingModal')

<div class="row" v-cloak >



	<div class="col-md-4 form-group">           
			<label for="session">Session </label> 
			<select {{$readonly}} tabindex="0" class ="form-control" name="session" v-model= "form.session" required v-on:change="sessionchanged">
				
				@foreach ($sessions as $session)
				    @php
				    $kla = substr($session,0, strpos($session,'.'));
				    $session_no = substr($session,strpos($session,'.')+1);
				    @endphp
					@if ($loop->index == 1)
						<option value={{$session}} selected>KLA-{{ $kla }}, Session: <strong>{{ $session_no }}</option>
					@else
						<option value={{$session}}>KLA-{{ $kla }}, Session: <strong>{{ $session_no }}</strong></option>
					@endif
					
				@endforeach
			</select>
	</div>
	<div class="col-md-4 form-group">  
		<label for="duty_date">Date From</label>
		<input class="form-control" :value="form.date_from" readonly required>
					
		<!-- <date-picker readonly v-model="form.date_from" @dp-change="onChange"
				:config="configdate"
				placeholder="Select date"
				:required="true"                
				class="form-control">
		</date-picker>  -->
	</div>
	<div class="col-md-4 form-group">  	
		<label for="duty_date">Date To</label>
<!-- 				
		<date-picker readonly v-model="form.date_to" @dp-change="onChange"
				:config="configdate"
				placeholder="Select date"
				:required="true"                
				class="form-control">
		</date-picker>  -->
		<input class="form-control" :value="form.date_to" readonly required>

		</select>
	</div>		
	
</div>

<div class="row" v-cloak>


	<div class="col-md-12">
		<table class="table  table-sm ">
			<thead v-show="form.overtimes.length" >
				<tr class="text-center" style="font-size: 12px; font-weight: bold">
					<th>No</th>
					<th>PEN - Name</th>
					<th>Designation</th>
					<th>Period-From, Period-To</th>
					<th>Sitting days attended</th>
					
					<!-- <th>Remarks if any</th> -->
				

					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row, index) in form.overtimes" :class="{ info: index%2 }">
					<td class="text-center align-middle" style="width:1px;"> <small><span v-text="index+1"></span></small></td>

					<td class="col-sm-3"> 
					<multiselect :name="'name[' + index + ']'" :id=index  :ref="'field-'+index"  v-model= "row.pen" 
					placeholder= "Type to search" 
					:options="pen_names" 
					:tabindex="1" 

					:show-labels="false"  
					:close-on-select="true" 
					:allow-empty="false" 
					:options-limit="100" 
					
					@select="changeSelect" 

					@search-change="asyncFind">
					<span slot="noResult"></span>
					</multiselect></td>

					<td class="col-sm-2" >
			
					<input class="form-control" style="font-size: 12px;" :value="row.designation" readonly >
					</td>

					
					<td class="col-sm-4">
					<div class="input-group" >
					<date-picker v-model="row.from" :config="configdate" 
						@dp-hide="onRowPeriodChange(index)"
						:required="true"
						placeholder="Period from which statement is related"
						class="form-control" >
					</date-picker> 
				
					<date-picker v-model="row.to" :config="configdate"
						@dp-hide="onRowPeriodChange(index)"
						:required="true"
						placeholder="Period to which statement is related"                
						class="form-control">
					</date-picker> 
					</div>
					</td>
			
					
					<td  class="col-sm-2" > 
					
					

					<div  class="input-group">
						<input :readonly="row.isProcessing"  type="number"  :name="'count[' + index + ']'" class="form-control"  min=1 oninput="validity.valid||(value='');" v-model="row.count" >
						<div class="input-group-append">

						<!-- <button v-if="row.punching && row.pen" :disabled='!row.from || !row.to' class="btn btn-sm btn-dark" id="show-modal" @click.prevent="showSittingOTs(index);"><i class="fas fa-fingerprint"></i>  </button> -->
						
						<a v-if="row.punching && row.pen"  :disabled='!row.from || !row.to || row.isProcessing'  href="#sittingotmodal" role="button" class="btn btn-sm btn-dark" @click.prevent="showSittingOTs(index);"><i class="fas fa-fingerprint"></i> </a>
						<a id="modalOpenBtn" hidden  href="#sittingotmodal" role="button" data-toggle="modal"><i class="fas fa-fingerprint"></i> </a>
					
						</div>
					</div>
							

					</td>
					
					<!-- <td class="col-md-2"> <input :name="'worknature[' + index + ']'" class="form-control" type="text" v-model="row.worknature" maxlength="180"></td> -->

					<td class="text-center  align-middle" style="width:1px;"><button class="btn  btn-danger" @click.prevent="removeElement(index);"><i class="fa fa-times"></i></button></td>


				</tr>

			</tbody>
			
		</table>

		<div class="row" v-cloak>
			<div class="col-md-12 form-group">
				
				<div >
					
					<div class="alert alert-danger" v-if="errors.products_empty" >
					<ul>
				        <li  v-cloak v-for="error in errors.products_empty">@{{ error }}</li>
				    </ul>
					</div>

					

					<div class="alert alert-warning" v-if="myerrors.length">
				        <ul>
				            <li  v-cloak v-for="error in myerrors">@{{ error }}</li>
				        </ul>
				    </div>
					
				</div>

			</div>
        </div>


		<div class="row" v-cloak>
			<div class="col-md-12 form-group">
				<button type="button" class="btn btn-success btn-sm" @click.prevent="addRow"><i class="fa fa-plus"></i> Add row</button>
			</div>
        </div>

        <div class="row" v-cloak>
        	<div class="col-md-12 form-group">
        		<label for="comment">Remarks:</label>
        		<input class="form-control" :rows="1" v-model="form.remarks" maxlength="190"  placeholder="If any (max 190 chars)"></input>
        	</div>
        </div>

	</div>
		
</div>
