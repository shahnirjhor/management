
<table>
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Item SKU</th>
            <th>Sale Price</th>
            <th>Purchase Price</th>
            <th>Quantity</th>
            <th>Tax</th>
            <th>Category</th>
            <th>Enabled</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->sku }}</td>
                <td>{{ $item->sale_price }}</td>
                <td>{{ $item->purchase_price }}</td>
                <td>{{ $item->quantity }}</td>
                @php
                    if(isset($item->tax->name) && !empty($item->tax->name)) {
                        $tax = $item->tax->name;
                    } else {
                        $tax = "No Tax";
                    }
                @endphp
                <td>{{ $tax }}</td>
                <td>{{ $item->category->name }}</td>
                <td>{{ ($item->enabled == '1') ? "Enable" : "Disable" }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
