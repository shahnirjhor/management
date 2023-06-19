<script>
    "use strict";

    $(document).ready(function() {
        var school_or_college = $('#school_or_college').val();
        if(school_or_college == '1') {
            $('#school_block').show();
            $('#college_block').hide();
        } else {
            $('#school_block').hide();
            $('#college_block').show();
        }

        $('#school_or_college').change(function(){
            if($('#school_or_college').val() == '1') {
                $('#school_block').show();
                $('#college_block').hide();
            } else {
                $('#school_block').hide();
                $('#college_block').show();
            }
        });

        var quill = new Quill('#input_address', {
            theme: 'snow'
        });

        var address = $("#address").val();
        quill.clipboard.dangerouslyPasteHTML(address);
        quill.root.blur();
        $('#input_address').on('keyup', function(){
            var input_address = quill.container.firstChild.innerHTML;
            $("#address").val(input_address);
        });

        $(".flatpickr").flatpickr({
            enableTime: false
        });

        $('#from_submit').click(function(){
            var school_or_college = $('#school_or_college').val();
            if(school_or_college == '1') {
                var scholarship_school_id = $('#scholarship_school_id').val();
                if(scholarship_school_id == "" || scholarship_school_id== undefined){
                    alert("Please Select The School")
                }
            } else {
                var scholarship_college_id = $('#scholarship_college_id').val();
                if(scholarship_college_id == "" || scholarship_college_id== undefined){
                    alert("Please Select The College")
                }
            }
        });


        $(".select2").select2();
    });
</script>
