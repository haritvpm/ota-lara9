
<script>
    window.deleteButtonTrans = '{{ trans("quickadmin.qa_delete_selected") }}';
</script>

<script type="text/javascript" src="{{ URL::asset('js/vue.min.js') }}"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script> -->
<script type="text/javascript" src="{{ URL::asset('js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/select2.full.min.js') }}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script> -->
<script type="text/javascript" src="{{ URL::asset('js/moment.min.js') }}"></script>
    <!-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> -->
<script type="text/javascript" src="{{ URL::asset('js/jquery.dataTables.min.js') }}"></script>
    <!-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> -->
<script type="text/javascript" src="{{ URL::asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script> -->
<script type="text/javascript" src="{{ URL::asset('js/dataTables.buttons.min.js') }}"></script>
    <!-- <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script> -->
<script type="text/javascript" src="{{ URL::asset('js/dataTables.select.min.js') }}"></script>
    <!-- <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script> -->
    
    <!-- <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script> -->
<script type="text/javascript" src="{{ URL::asset('js/buttons.print.min.js') }}"></script>

    <!-- <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script> -->
    <!-- <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script> -->
    <!-- <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script> -->
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> -->
<script type="text/javascript" src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script> -->
    <!-- <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script> -->
  

    <!-- <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>  -->
<!-- <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.5.3/dist/vue.js"></script> -->


<!-- <script type="text/javascript" src="{{ URL::asset('js/lodash.min.js') }}"></script> -->


<!-- <script type="text/javascript" src="{{ URL::asset('js/lodash2.4.2.min.js') }}"></script> -->
<!-- underscore instead of loadash -->

    <!-- <script src="{{ asset('js/app.js') }}"></script> -->
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        $(function() {
  let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
  let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
  let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
  let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
  let printButtonTrans = '{{ trans('global.datatables.print') }}'
  let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
  let selectAllButtonTrans = '{{ trans('global.select_all') }}'
  let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

  // let languages = {
  //   'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
  // };

  $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
  $.extend(true, $.fn.dataTable.defaults, {
    // language: {
    //   url: languages['{{ app()->getLocale() }}']
    // },
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }, {
        orderable: false,
        searchable: false,
        targets: -1
    }],
    select: {
      style:    'multi+shift',
      selector: 'td:first-child'
    },
    order: [],
    scrollX: true,
    pageLength: 100,
    dom: 'lBfrtip<"actions">',
    buttons: [
      {
        extend: 'selectAll',
        className: 'btn-primary',
        text: selectAllButtonTrans,
        exportOptions: {
          columns: ':visible'
        },
        action: function(e, dt) {
          e.preventDefault()
          dt.rows().deselect();
          dt.rows({ search: 'applied' }).select();
        }
      },
      {
        extend: 'selectNone',
        className: 'btn-primary',
        text: selectNoneButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'copy',
        className: 'btn-default',
        text: copyButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'csv',
        className: 'btn-default',
        text: csvButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'excel',
        className: 'btn-default',
        text: excelButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'pdf',
        className: 'btn-default',
        text: pdfButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'print',
        className: 'btn-default',
        text: printButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'colvis',
        className: 'btn-default',
        text: colvisButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      }
    ]
  });

  $.fn.dataTable.ext.classes.sPageButton = '';
});

    </script>
    
<script>
   jQuery.extend(true, jQuery.fn.datetimepicker.defaults, {
    icons: {
      time: 'far fa-clock',
      date: 'far fa-calendar',
      up: 'fas fa-arrow-up',
      down: 'fas fa-arrow-down',
      previous: 'fas fa-chevron-left',
      next: 'fas fa-chevron-right',
      today: 'fas fa-calendar-check',
      clear: 'far fa-trash-alt',
      close: 'far fa-times-circle'
    }
});
</script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script> -->
    <script type="text/javascript" src="{{ URL::asset('js/adminlte.min.js') }}"></script>




<script type="text/javascript" src="{{ URL::asset('js/datatable_custom.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('js/vue-multiselect.min.js') }}"></script>
<!-- <script src="https://unpkg.com/vue-multiselect@2.1.6/dist/vue-multiselect.min.js"></script> -->

<script type="text/javascript" src="{{ URL::asset('js/underscore-1.8.3-min.js') }}"></script>
 <script type="text/javascript" src="{{ URL::asset('js/axios.min.js') }}"></script>
<script src="{{ URL::asset('js/vue-bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/vue-sweetalert.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/vue-bootstrap-datetimepicker@5"></script> -->



@yield('javascript')
