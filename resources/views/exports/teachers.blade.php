
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Institution</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Gender</th>
            <th>Blood Group</th>
            <th>Date Of Birth</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($teachers as $teacher)
            <tr>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->email }}</td>
                <td>{{ ($teacher->school_or_college == '1') ? "School" : "College" }}</td>
                <td>{{ $teacher->phone }}</td>
                <td>{{ $teacher->address }}</td>
                <td>{{ $teacher->gender }}</td>
                <td>{{ $teacher->blood_group }}</td>
                <td>{{ $teacher->date_of_birth }}</td>
                <td>{{ ($teacher->status == '1') ? "Active" : "Inactive" }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
