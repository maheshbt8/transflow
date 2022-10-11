<!-- jQuery -->
<script src="{{ asset('admin') }}/plugins/jquery/jquery.min.js"></script>
<!-- jquery-validation -->
<script src="{{ asset('admin') }}/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="{{ asset('admin') }}/plugins/jquery-validation/additional-methods.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('admin') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="{{ asset('admin') }}/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="{{ asset('admin') }}/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="{{ asset('admin') }}/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="{{ asset('admin') }}/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('admin') }}/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="{{ asset('admin') }}/plugins/moment/moment.min.js"></script>
<script src="{{ asset('admin') }}/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('admin') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="{{ asset('admin') }}/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('admin') }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

<!-- Toastr -->
<script src="{{ asset('admin') }}/plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin') }}/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('admin') }}/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('admin') }}/dist/js/pages/dashboard.js"></script>

<!-- Select2 -->
<script src="{{ asset('admin') }}/plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{ asset('admin') }}/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="{{ asset('admin') }}/plugins/inputmask/jquery.inputmask.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="{{ asset('admin') }}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('admin') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('admin') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('admin') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('admin') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('admin') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="{{ asset('admin') }}/plugins/jszip/jszip.min.js"></script>
<script src="{{ asset('admin') }}/plugins/pdfmake/pdfmake.min.js"></script>
<script src="{{ asset('admin') }}/plugins/pdfmake/vfs_fonts.js"></script>
<script src="{{ asset('admin') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="{{ asset('admin') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="{{ asset('admin') }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script type="text/javascript">
  $(function () {
    window._token = $('meta[name="csrf-token"]').attr('content');
    $(".datatable_table").DataTable({
      'bPaginate'     : true,
    'bLengthChange'   : false,
    'bFilter'         : true,
    'bInfo'           : true,
    'bAutoWidth'      : false,
    'aoColumnDefs'    : [
      {
        'bSortable'   : false,
        'aTargets'    : ['nosorting']
      }],
    'aaSorting'     : [],
      "buttons": [
                {
                extend: 'copy',
                exportOptions: {
                    columns: ':visible',
                    columns: "thead th:not(.noExport)"
                    }, footer: true
                },
                {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible',
                    columns: "thead th:not(.noExport)"
                    }, footer: true
                },
                {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible',
                    columns: "thead th:not(.noExport)"
                    }, footer: true
                },
                {
                extend: 'pdf',
                exportOptions: {
                    columns: ':visible',
                    columns: "thead th:not(.noExport)"
                    }, footer: true
                },
                {
                extend: 'print',
                exportOptions: {
                    columns: ':visible',
                    columns: "thead th:not(.noExport)"
                }, footer: true
            }, "colvis"],columnDefs: [
            { targets:'ext-th-data', visible: false }
        ]
    }).buttons().container().appendTo('#DataTables_Table_0_wrapper .col-md-6:eq(0)');
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });
    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    }) 

    <?php 
      if(isset($sdate) && $sdate != ''){
      ?>
      var startdate='{{ $sdate }}';
      var sdate=moment(startdate, ["Y-M-D"]);
    <?php }else{ ?>
      var sdate=moment().subtract(29, 'days');
      <?php }?>
    <?php 
      if(isset($edate) && $edate != ''){
      ?>
      var enddate='{{ $edate }}';
      var edate=moment(enddate, ["Y-M-D"]);
    <?php }else{ ?>
      var edate=moment();
      <?php }?> 
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: sdate,
        endDate  : edate
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        filterofdaterange(start, end);
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    //$('.my-colorpicker1').colorpicker()
    //color picker with addon
   // $('.my-colorpicker2').colorpicker()

   /* $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    });*/

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

  

/*Form Validation Code*/
 $(".needs-validation").submit(function() {
    var form = $(this);
    if (form[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    }
    form.addClass("was-validated");
  });
/*Form Validation Code Ends*/

$('.select_org_list').change(function(){
var org_id=$(this).val();
  $.ajax({
    url:"{{ route('admin.kptsuborganizations.suborganizations_org') }}",
    method:"POST",
    data:{"org_id":org_id},
    headers: {'x-csrf-token': _token},
    success:function(response){
      $('#sub_organization_list').html(response);
    }
  });
});
$('.select_suborg_list').change(function(){
var sub_org_id=$(this).val();
  $.ajax({
    url:"{{ route('admin.kptdepartments.departments_suborg') }}",
    method:"POST",
    data:{"sub_org_id":sub_org_id},
    headers: {'x-csrf-token': _token},
    success:function(response){
      $('#department_list').html(response);
    }
  });
});

$('.select_client_org_list').change(function(){
var org_id=$(this).val();
  $.ajax({
    url:"{{ route('admin.clientsuborganization.clientsuborganizations_org') }}",
    method:"POST",
    data:{"org_id":org_id},
    headers: {'x-csrf-token': _token},
    success:function(response){
      $('#client_sub_organization_list').html(response);
    }
  });
});
           $("#email_validation").click(function() {
          var regexEmail = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
          var email = document.getElementById("email");
          if (regexEmail.test(email.value)) {
            return true;
               } else {
                $('#error_on_header').html('<div class="alert alert-error alert-msg"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter correct Email Address</div>');
                $("#email").focus();
                return false;
                 }
                });


})
</script>


