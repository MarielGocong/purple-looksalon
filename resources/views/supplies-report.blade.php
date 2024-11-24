<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

    <title>Supplies and Consumables Report</title>
    <style>
        /* Add styles for the PDF layout */
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Supplies Report</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Color Code</th>
                <th>Color Shade</th>
                <th>Size</th>
                <th>Expiration Date</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($supplies as $supply)
                <tr>
                    <td>{{ $supply->name }}</td>
                    <td>{{ $supply->category->name ?? 'N/A' }}</td>
                    <td>{{ $supply->description }}</td>
                    <td>{{ $supply->quantity }}</td>
                    <td>{{ $supply->color_code }}</td>
                    <td>{{ $supply->color_shade }}</td>
                    <td>{{ $supply->size }}</td>
                    <td>{{ $supply->expiration_date }}</td>
                    <td>{{ $supply->online_supplier->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
