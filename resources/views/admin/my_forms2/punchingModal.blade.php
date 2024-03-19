<template id="my-modal">
    <div class="modal fade" id="sittingotmodal" role="dialog">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          
            <h5 class="modal-title" >@{{modaldata_row?.pen}} (@{{modaldata_row?.designation}}) </h5>

	          <button type="button" class="close"   data-dismiss="modal">&times;</button>
	        </div>
	        <div class="modal-body">
          <table class="table table-sm ">
            <thead>
              <tr>
              <th scope="col"></th>
              <th scope="col">Date</th>
              <th scope="col">Punchin</th>
              <th scope="col">Punchout</th>
              <th scope="col">OT</th>
              </tr>
            </thead>
            <tbody>
            <tr v-bind:class="{'table-secondary' : !item.userdecision }" v-for="(item, index) in modaldata" :key="item.date">
              <td>@{{ index+1}}</td>
              <td>@{{ item.date }}</td>
              <td>@{{ item.punchin }}</td>
              <td>@{{ item.punchout }}</td>
              <td v-if = "modaldata_showonly || !item.userdecision" >
              @{{ item.ot }}
              </td>
              <td v-else class="align middle" >
                  <input type="radio"  value="NO" v-model="item.ot" :id="`no${item.date}`">
                  <label :for="`no${item.date}`">NO</label>
                  <input type="radio" value="YES" v-model="item.ot" :id="`yes${item.date}`">
                  <label :for="`yes${item.date}`">Yes</label>
                <!-- <input type="checkbox"  :value="item.date" v-model="modaldata_seldays"> -->
                <!-- <label >Yes</label> -->
              </td>
            </tr>
            
            </tbody>
           
          </table>
          </div>
	        <div class="modal-footer d-flex justify-content-between ">
            Total OT: @{{yesModalDays.length}} / @{{modaldata_totalOTDays}}
           <!-- <div> -->
	          <button type="button" class="btn btn-danger" :disabled="yesAndNodaysModalDays.length != modaldata.length" @click="modalClosed" data-dismiss="modal">OK</button>
            <!-- <button type="button" class="btn btn-secondary" v-show="yesAndNodaysModalDays.length != modaldata.length"   data-dismiss="modal">Close</button> -->
            <!-- </div> -->
	        </div>
	      </div>
	    </div>
	</div>
</template>