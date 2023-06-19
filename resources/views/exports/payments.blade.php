<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Vendor</th>
            <th>Category</th>
            <th>Account</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ date($companyDateFormat, strtotime($payment->paid_at)) }}</td>
                <td>@money($payment->amount, $payment->currency_code, true)</td>
                <td>{{ $payment->vendor->name}}</td>
                <td>{{ $payment->category->name}}</td>
                <td>{{ $payment->account->name}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
