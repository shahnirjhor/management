<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Code</th>
            <th>Rate</th>
            <th>Symbol</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($currencies as $currency)
            <tr>
                <td>{{ $currency->id }}</td>
                <td>{{ $currency->name }}</td>
                <td>{{ $currency->code }}</td>
                <td>{{ $currency->rate }}</td>
                <td>{{ $currency->symbol }}</td>
                @php
                    ($currency->enabled == '1') ? $status = "Enable" : $status = "Disable";
                @endphp
                <td>{{ $status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
