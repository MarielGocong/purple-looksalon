<!DOCTYPE html>
<html>
<head>
    <title>Monthly Sales Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            text-align: center;
            padding: 8px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Monthly Sales Report</h1>

    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Sales</th>
                <th>Services Rendered</th>
                <th>Employees Involved</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->month }}</td>
                <td>{{ $report->month_start }}</td>
                <td>{{ $report->month_end }}</td>
                <td>{{ number_format($report->total_sales, 2) }}</td>
                <td>{{ $report->services }}</td>
                <td>{{ $report->employees }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Prepared By:</strong> {{ $preparedBy }}</p>
        <p><strong>Report Date & Time:</strong> {{ $currentDateTime }}</p>
    </div>
</body>
</html>
