<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Date</th>
            <th>From Account</th>
            <th>To Account</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transfers as $transfer)
            <tr>
                <td>{{ $transfer->id }}</td>
                <td>{{ date($companyDateFormat, strtotime($transfer->payment->paid_at)) }}</td>
                <td>{{ $transfer->payment->account->name }}</td>
                <td>{{ $transfer->revenue->account->name }}</td>
                <td>@money($transfer->payment->amount, $transfer->payment->currency_code, true)</td>
            </tr>
        @endforeach
    </tbody>
</table>
