<script>
    "use strict";
    $(document).ready( function () {
        $('#laravel_datatable').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excelHtml5', exportOptions: {columns: [ 1,2,3,4 ]}},
                { extend: 'csvHtml5', exportOptions: {columns: [ 1,2,3,4 ]}},
                { extend: 'pdfHtml5', exportOptions: {columns: [ 1,2,3,4 ]}}
            ]
        });
    });
</script>
