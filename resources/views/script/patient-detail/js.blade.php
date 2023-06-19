<script>
    "use strict";
    
    $(document).ready(function() {
        var quill = new Quill('#biography', {
            theme: 'snow',
        });

        quill.root.innerHTML = $('#dText').val();
        quill.root.blur();
        $(document).on('submit', '#departmentForm', function(e){
            $('#dText').val(quill.container.firstChild.innerHTML);
        });
    });
</script>
