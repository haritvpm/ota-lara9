@extends('layouts.app')


@section('content')



  <div class="card p-2" id="app">
        <div class="card-title">
            Fetch Punching API
        </div>

        <div class="card-body">

	<form action="{{url('admin/punchings/fetchApi')}}" method="get" id="filter" class="form-inline">
        
        <div class="form-group">     
            ApiNum <input required class="form-control" placeholder="1 for empl/5 for atten/6 for trace" name = "apinum" value="{{ \Request('apinum')}}" >
		</div>

        <div class="form-group">     
        	Date (for 5/6) <input class="form-control" placeholder="dd-mm-yyyy" name = "reportdate" value="{{ \Request('reportdate')}}" >
		</div>
	  
        <div class="form-group">                                
                  
        <button type="submit" class="btn btn-danger" rel="filter"><i class="fa fa-refresh" aria-hidden="true"></i></button>

        </div>
    </form>

  </div>
  </div>
  
  <div class="" id="app">
    <!-- <h3 class="page-title">Search</h3> -->
     
 <div class=" ">
    
    <table class=" table table-borderless table-striped  ajaxTable" style="width:100%">
                <thead>
                    <tr>
                       
                        <th>
                            {{ trans('cruds.punching.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.punching.fields.date') }}
                        </th>
                        <th>
                            {{ trans('cruds.punching.fields.punch_in') }}
                        </th>
                        <th>
                            {{ trans('cruds.punching.fields.punch_out') }}
                        </th>
                        <th>
                            AttendanceId
                        </th>
                       
                        <th>
                            {{ trans('cruds.punching.fields.pen') }}
                        </th>
                      
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                
            </table>
            </div>
  </div>
  </div>
  

<!-- export -->

<!-- 
cannot trust form no, as a user might have started a form, but waited long to submit it. so submit date is the key. -->


@stop


@section('javascript') 


<script type="text/javascript">

// $(function () {
//   let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

//   $.extend(true, $.fn.dataTable.defaults, {
//     orderCellsTop: true,
//     order: [[ 1, 'desc' ]],
//     pageLength: 100,
//   });
//   let table = $('.datatable-Punching:not(.ajaxTable)').DataTable({ buttons: dtButtons })
//   $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
//       $($.fn.dataTable.tables(true)).DataTable()
//           .columns.adjust();
//   });
  
// })

$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const myParam = urlParams.get('datefilter');

            window.dtDefaultOptions.ajax = {
                url: '{!! route('admin.punchings.index') !!}',
                data: {
                    datefilter: myParam
                }
            } 
            window.dtDefaultOptions.columns = [
             /*   @can('employee_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan*/
                // {data: 'srismt', name: 'srismt'},
                
                {data: 'id', name: 'id'},
                {data: 'date', name: 'date'},
                {data: 'punch_in', name: 'punch_in'},
                {data: 'punch_out', name: 'punch_out'},
                {data: 'aadhaarid', name: 'aadhaarid'},
                {data: 'pen', name: 'pen'},

            
              
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });

</script>


@endsection