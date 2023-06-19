<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Number</th>
            <th>Current Balance</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($accounts as $account)
            <tr>
                <td>{{ $account->id }}</td>
                <td>{{ $account->name }}</td>
                <td>{{ $account->number }}</td>
                <td>@money($account->balance, $account->currency_code, true)</td>
                @php
                    ($account->enabled == '1') ? $status = "Enable" : $status = "Disable";
                @endphp
                <td>{{ $status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
