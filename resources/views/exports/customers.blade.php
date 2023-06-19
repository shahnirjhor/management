
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
        @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->Unpaid }}</td>
                <td>{{ ($customer->enabled == '1') ? "Enable" : "Disable" }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
