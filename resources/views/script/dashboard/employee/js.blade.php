<script>
    $(document).ready(function() {
        var employeeDataName = {!! $employeeDataName !!};
        var employeeDataValue = {!! $employeeDataValue !!};
        var employeeDataColor = {!! $employeeDataColor !!};

        var departmentDataName = {!! $departmentDataName !!};
        var departmentDataValue = {!! $departmentDataValue !!};
        var departmentDataColor = {!! $departmentDataColor !!};

        var employee_config = {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: employeeDataValue,
                    backgroundColor: employeeDataColor
                }],
                labels: employeeDataName
            },
            options: {
                legend: {
                    display: false
                },
                responsive: true
            }
        };

        var department_config = {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: departmentDataValue,
                    backgroundColor: departmentDataColor
                }],
                labels: departmentDataName
            },
            options: {
                legend: {
                    display: false
                },
                responsive: true
            }
        };

        window.onload = function() {
            var location_area = document.getElementById('location_area').getContext('2d');
            window.myPie = new Chart(location_area, employee_config);
            var department_area = document.getElementById('department_area').getContext('2d');
            window.myPie = new Chart(department_area, department_config);
        };
    });
</script>