<template id="my-modal">
    <div class="modal fade" id="sittingotmodal" role="dialog">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          
            <h5 class="modal-title" >SittingDays OT - @{{modaldata_row?.pen}} </h5>

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
            <tr v-bind:class="{'table-secondary' : item.otna }" v-for="(item, index) in modaldata" :key="item.date">
              <td>@{{ index+1}}</td>
              <td>@{{ item.date }}</td>
              <td>@{{ item.punchin }}</td>
              <td>@{{ item.punchout }}</td>
              <td v-if = "modaldata_showonly || !item.otna" >
              @{{ item.ot }}
              </td>
              <td v-else >
                <input type="checkbox"  :value="item.date" v-model="modaldata_seldays">
                <label >Yes</label>
              </td>
            </tr>
            
            </tbody>
           
          </table>
          </div>
	        <div class="modal-footer d-flex justify-content-between ">
            Total OT: @{{modaldata_seldays.length}} / @{{modaldata_totalOTDays}}
	          <button type="button" class="btn btn-danger"  @click="modalClosed" data-dismiss="modal">OK</button>
	        </div>
	      </div>
	    </div>
	</div>
</template>