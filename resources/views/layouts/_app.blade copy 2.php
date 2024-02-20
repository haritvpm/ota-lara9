<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title></title>

  <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
    rel="stylesheet" /> -->
    

   <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
   <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
   
  
   <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet" />
  
  <link href="{{ URL::asset('css/adminltev3.css') }}" rel="stylesheet" />
  
  <link href="{{ asset('css/all.css') }}"  rel="stylesheet" />
  
  <!-- <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" /> -->
<!--   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />  -->
  <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/select.dataTables.min.css') }}" rel="stylesheet" />


  <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

  @yield('styles')
  <script>
          window.Laravel = <?php echo json_encode([
              'csrfToken' => csrf_token(),
          ]); ?>
        </script>
  <style>
.table>tbody>tr>td,
.table>tbody>tr>th {
  border-top: none;
  /*border-bottom: none;*/
}

</style>
</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
      </ul>

  
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <i class="fa fa-user"></i> {{ auth()->user()->name }} ({{Auth::user()->DispNameWithName}})
        </li>
      </ul>

    </nav>

    @include('partials.sidebar')
    <div class="content-wrapper" style="min-height: 917px;">
      <!-- Main content -->
      <section class="content" style="padding-top: 20px">
        @if(session('message'))
        <div class="row mb-2">
          <div class="col-lg-12">
            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
          </div>
        </div>
        @endif
        @if($errors->count() > 0)
        <div class="alert alert-danger">
          <ul class="list-unstyled">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        @yield('content')
      </section>
      <!-- /.content -->
    </div>

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 0.3
      </div>
      <strong> &copy;</strong> {{ trans('global.allRightsReserved') }}
    </footer>
    <form id="logoutform" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
     
    </form>
  </div>
  <script src="{{ asset('js/app.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('js/vue.min.js') }}"></script>

  <script src="{{ asset('js/cdnjs/jquery.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/popper.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/select2.full.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/moment.min.js') }} "></script>
  <script src="{{ asset('js/cdnjs/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/dataTables.bootstrap5.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/dataTables.select.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/buttons.flash.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/buttons.print.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/buttons.colVis.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/pdfmake.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/vfs_fonts.js') }}"></script>
  <script src="{{ asset('js/cdnjs/jszip.min.js') }}"></script>
  <!-- <script src="{{ asset('js/cdnjs/ckeditor.js') }}"></script> -->
  <script src="{{ asset('js/cdnjs/bootstrap-datetimepicker.min.js') }}"></script>
  <script src="{{ asset('js/cdnjs/dropzone.min.js') }}"></script>
 
  <script src="{{ asset('js/cdnjs/adminlte.min.js') }}"></script>
 <script src="{{ asset('js/main.js') }}"></script>

 <script type="text/javascript" src="{{ URL::asset('js/axios.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/vue-multiselect.min.js') }}"></script>
  <script>
    $(function () {
      let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
      let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
      let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
      let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
      let printButtonTrans = '{{ trans('global.datatables.print') }}'
      let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
      let selectAllButtonTrans = '{{ trans('global.select_all') }}'
      let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

      let languages = {
        'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
      };

      $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
      $.extend(true, $.fn.dataTable.defaults, {
        language: {
          url: languages['{{ app()->getLocale() }}']
        },
        columnDefs: [/* {
          orderable: false,
          className: 'select-checkbox',
          targets: 0
        }, */ {
          orderable: false,
          searchable: false,
          targets: -1
        }],
        select: {
          style: 'multi+shift',
          selector: 'td:first-child'
        },
        order: [],
        scrollX: true,
        pageLength: 100,
        dom: 'lBfrtip<"actions">',
        buttons: [
          /* {
            extend: 'selectAll',
            className: 'btn-primary',
            text: selectAllButtonTrans,
            exportOptions: {
              columns: ':visible'
            },
            action: function (e, dt) {
              e.preventDefault()
              dt.rows().deselect();
              dt.rows({ search: 'applied' }).select();
            }
          }, */
        /*   {
            extend: 'selectNone',
            className: 'btn-primary',
            text: selectNoneButtonTrans,
            exportOptions: {
              columns: ':visible'
            }
          }, */
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
        /*  {
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
          }*/
        ]
      });

      $.fn.dataTable.ext.classes.sPageButton = '';
    });

  </script>
  
  @yield('scripts')





</body>

</html>
