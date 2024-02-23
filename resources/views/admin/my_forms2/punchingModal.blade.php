<template id="my-modal">
    <div class="modal fade" id="reject" role="dialog">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          
            <h5 class="modal-title" >SittingDays OT - @{{modaldata_empl}} </h5>

	          <button type="button" class="close" data-dismiss="modal">&times;</button>
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
            <tr v-for="(item, index) in modaldata" :key="item.date">
              <td>@{{ index+1}}</td>
              <td>@{{ item.date }}</td>
              <td>@{{ item.punchin }}</td>
              <td>@{{ item.punchout }}</td>
              <td>@{{ item.ot }}</td>
            </tr>
            
            </tbody>
           
          </table>
          </div>
	        <div class="modal-footer d-flex justify-content-between ">
            Total OT: @{{modaldata_totalOT}} / @{{modaldata_totalOTDays}}
	          <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
	        </div>
	      </div>
	    </div>
	</div>
</template>