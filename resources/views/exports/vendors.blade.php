
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Unpaid</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vendors as $vendor)
            <tr>
                <td>{{ $vendor->name }}</td>
                <td>{{ $vendor->email }}</td>
                <td>{{ $vendor->phone }}</td>
                <td>{{ $vendor->Unpaid }}</td>
                <td>{{ ($vendor->enabled == '1') ? "Enable" : "Disable" }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
