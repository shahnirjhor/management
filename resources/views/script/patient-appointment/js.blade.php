<script>
    "use strict";
    $(document).ready(function() {
        $(document).on('change', '#doctor_id, #appointment_date', function() {
            let userId = $('#doctor_id').val();
            let appointmentDate = $('#appointment_date').val();
            let url = "{{ route('patient-appointments.getScheduleDoctorWise') }}";
            if (userId && appointmentDate)
                $.get(url, {userId, appointmentDate},function(data, status){
                    $('#appointment_slot').html(data);
                });
        });
    });
</script>
