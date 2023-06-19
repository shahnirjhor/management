<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Rate</th>
            <th>Type</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($taxes as $tax)
            <tr>
                <td>{{ $tax->id }}</td>
                <td>{{ $tax->name }}</td>
                <td>{{ $tax->rate }}</td>
                <td>{{ $tax->type }}</td>
                @php
                    ($tax->enabled == '1') ? $status = "Enable" : $status = "Disable";
                @endphp
                <td>{{ $status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
