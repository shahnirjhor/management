<script>

$('#myModal').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('action', $(e.relatedTarget).data('href'));
});

"use strict";
$(document).ready( function () {
    $('#laravel_datatable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: "{{ url('category-list') }}",
        type:'POST', 'headers': { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'code', name: 'code' },
            { data: 'color', name: 'color' },
            { data: 'parent', name: 'parent' },
            { data: 'enabled', name: 'enabled' },
            @php  //  @canany(['smtp-delete', 'smtp-edit']) @endphp
            { data: 'action', name: 'action' }
            @php  //  @endcan @endphp
        ]
    });
});

</script>