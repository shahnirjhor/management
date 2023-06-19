<script src="{{ asset('plugins/alertifyjs/alertify.min.js') }}"></script>
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>


<script>
        function selectChange(val) {
            $('#myForm').submit();
        }

        $(document).on('click', '#doPrint', function(){
            var printContent = $('#print-area').html();
            $('body').html(printContent);
            window.print();
            location.reload();
        });

        $(document).on('click', '#doDownload', function(){
            var printContent = $('#print-area').html();
            var file = $('body').html(printContent).download();
            var filename = "invoice.pdf";
            download(filename, file);
        });


</script>
