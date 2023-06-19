
<table>
    <thead>
        <tr>
            <th>College Name</th>
            <th>College Type</th>
            <th>Village</th>
            <th>District</th>
            <th>Email</th>
            <th>Website</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($colleges as $school)
            <tr>
                <td>{{ $school->name }}</td>
                <td>{{ $school->college_type }}</td>
                <td>{{ $school->village }}</td>
                <td>{{ $school->district }}</td>
                <td>{{ $school->email }}</td>
                <td>{{ $school->website }}</td>
                <td>{{ ($school->status == '1') ? "Active" : "Inactive" }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
