
<table>
    <thead>
        <tr>
            <th>School Name</th>
            <th>School Type</th>
            <th>Village</th>
            <th>District</th>
            <th>Email</th>
            <th>Website</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($schools as $school)
            <tr>
                <td>{{ $school->name }}</td>
                <td>{{ $school->school_type }}</td>
                <td>{{ $school->village }}</td>
                <td>{{ $school->district }}</td>
                <td>{{ $school->email }}</td>
                <td>{{ $school->website }}</td>
                <td>{{ ($school->status == '1') ? "Active" : "Inactive" }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
