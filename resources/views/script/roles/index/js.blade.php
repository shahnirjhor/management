<script>

    "use strict";
    $("#myModal").on("show.bs.modal", function (e) {
        $(this).find(".btn-ok").attr("action", $(e.relatedTarget).data("href"));
    });

    $(document).ready(function () {
        $("#laravel_datatable").DataTable({
            paging: false,
            lengthChange: false,
            searching: false,
            ordering: true,
            info: false,
            autoWidth: false,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excelHtml5', exportOptions: {columns: [ 0,1,2,3 ]}},
                { extend: 'csvHtml5', exportOptions: {columns: [ 0,1,2,3 ]}},
                { extend: 'pdfHtml5', exportOptions: {columns: [ 0,1,2,3 ]}}
            ]
        });
    });

</script>
