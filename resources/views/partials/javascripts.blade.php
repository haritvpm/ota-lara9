
<script>
    window.deleteButtonTrans = '{{ trans("quickadmin.qa_delete_selected") }}';
  
</script>

<!-- <script type="text/javascript" src="{{ URL::asset('js/jquery-2.2.3.min.js') }}"></script> -->

<script type="text/javascript" src="{{ URL::asset('js/jquery-1.11.3.min.js') }}"></script>


<!-- <script type="text/javascript" src="{{ URL::asset('js/lodash.min.js') }}"></script> -->


<!-- <script type="text/javascript" src="{{ URL::asset('js/lodash2.4.2.min.js') }}"></script> -->
<!-- underscore instead of loadash -->
<script type="text/javascript" src="{{ URL::asset('js/underscore-1.8.3-min.js') }}"></script>



<!-- <script src="//code.jquery.com/jquery-1.11.3.min.js"></script> -->
<!-- <script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script> -->
<script src="{{ url('js/jquery.dataTables.min.js') }}"></script>
<!-- <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script> -->
<!-- <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script> -->
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script> -->
<!-- <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script> -->
<!-- <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script> -->
<!-- <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script> -->
<script src="{{ url('js/dataTables.select.min.js') }}"></script>
<!-- <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script> -->
<!-- this is needed for jquery date component calender form -->
<script type="text/javascript" src="{{ URL::asset('js/jquery-ui-1.11.3.min.js') }}"></script>
<script src="{{ url('adminlte/js') }}/bootstrap.min.js"></script>
<script src="{{ url('adminlte/js') }}/select2.full.min.js"></script>
<script src="{{ url('adminlte/js') }}/main.js"></script>

<!-- <script src="{{ url('adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script> -->
<!-- <script src="{{ url('adminlte/plugins/fastclick/fastclick.js') }}"></script> -->
<script src="{{ url('adminlte/js/app.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('js/vue.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/axios.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/vue-multiselect.min.js') }}"></script>



<!-- for vue date time picker -->
<!-- <script src="https://unpkg.com/moment@2.18.1/min/moment.min.js"></script>
<script src="https://unpkg.com/eonasdan-bootstrap-datetimepicker@4.17.47/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://unpkg.com/vue-bootstrap-datetimepicker"></script> -->

<script src="{{ URL::asset('js/moment.min.js') }}"></script>
<!-- Date-picker itself -->
<script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ URL::asset('js/vue-bootstrap-datetimepicker.min.js') }}"></script>


<!-- <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script> 
 -->
 <!-- firefox 25 support -->
<script src="{{ URL::asset('js/es6-promise.auto.min.js') }}"></script>


<script>
    window._token = '{{ csrf_token() }}';
</script>
<!-- <script>
    $.extend(true, $.fn.dataTable.defaults, {
        "language": {
            "url": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/English.json"
        }
    });
</script> -->



@yield('javascript')
