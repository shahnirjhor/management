<script>
    "use strict";
    $(document).ready(function() {
        var quill = new Quill('#input_report', {
            theme: 'snow'
        });

        quill.root.innerHTML = $('#report').val();
        quill.root.blur();
        $(document).on('submit', '#labReportEditFrom', function(e){
            $('#report').val(quill.container.firstChild.innerHTML);
        });
    });
</script>
