<script>
    "use strict";

    $(document).ready(function() {
        var quill = new Quill('#input_report', {
            theme: 'snow'
        });

        $('.generate_report_template').on('change', function(){
            let itemName = "{{ $ApplicationSetting->item_name  }}";
            let patientId = $("#patient_id").val();
            let labReportTemplateId = $("#lab_report_template_id").val();
            let doctorId = $("#doctor_id").val();
            let date = $("#date").val();
            if(labReportTemplateId) {
                if(patientId=="") {
                    Swal.fire(
                        itemName,
                        '{{ __('Select Patient First') }}',
                        'warning'
                    );
                    return;
                }

                if(date=="") {
                    Swal.fire(
                        itemName,
                        '{{ __('Select Lab Report Date') }}',
                        'warning'
                    );
                    return;
                }

                if(labReportTemplateId=="") {
                    Swal.fire(
                        itemName,
                        '{{ __('Select Lab Report Template First') }}',
                        'warning'
                    );
                    return;
                }

                $.post("{{ url('labreport/generateTemplateData') }}",
                    {date,patientId,labReportTemplateId,doctorId},
                        function(data,status) {
                        if(data.status == '2') {
                            Swal.fire(
                                'Oops...',
                                data.message,
                                'error'
                            );
                            $('#lab_report_template_id').val("").change();
                            quill.clipboard.dangerouslyPasteHTML("");
                            $('#input_report').on('keyup', function(){
                                $("#report").val("");
                            });
                        }
                        if(data.status == '1') {
                            let report = data.message;
                            quill.root.innerHTML = report;
                            quill.root.blur();
                            $(document).on('submit', '#labReportFrom', function(e){
                                $('#report').val(quill.container.firstChild.innerHTML);
                            });
                        }
                });
            }
        });
    });
</script>
