<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Type</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $categorie)
            <tr>
                <td>{{ $categorie->id }}</td>
                <td>{{ $categorie->name }}</td>
                <td>{{ $categorie->type }}</td>
                @php
                    ($categorie->enabled == '1') ? $status = "Enable" : $status = "Disable";
                @endphp
                <td>{{ $status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
