<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

    <title>Equipment Report</title>
    <style>
        /* Add styles for the PDF layout */
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Equipment Report</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Brand Name</th>
                <th>Quantity</th>
                <th>Staff Assigned</th>
                <th>Last Maintenance</th>
                <th>Next Maintenance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equipments as $equipment)
                <tr>
                    <td>{{ $equipment->name }}</td>
                    <td>{{ $equipment->category->name ?? 'N/A' }}</td>
                    <td>{{ $equipment->brand_name }}</td>
                    <td>{{ $equipment->quantity }}</td>
                    <td>{{ $equipment->employee->first_name }}</td>
                    <td>{{ $equipment->last_maintenance }}</td>
                    <td>{{ $equipment->next_maintenance }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
