<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Customer</th>
            <th>Category</th>
            <th>Account</th>
        </tr>
    </thead>
    <tbody>
        @foreach($revenues as $revenue)
            <tr>
                <td>{{ $revenue->id }}</td>
                <td>{{ date($companyDateFormat, strtotime($revenue->paid_at)) }}</td>
                <td>@money($revenue->amount, $revenue->currency_code, true)</td>
                <td>{{ $revenue->customer->name}}</td>
                <td>{{ $revenue->category->name}}</td>
                <td>{{ $revenue->account->name}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
