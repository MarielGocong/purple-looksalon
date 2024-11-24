<!DOCTYPE html>
<html>
<head>
    <title>Annual Sales Report</title>
</head>
<body>
    <h1>Annual Sales Report</h1>
    <a href="{{ route('annual.report.pdf') }}" class="btn btn-primary" style="margin-bottom: 20px;">Download PDF</a>

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Year</th>
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
                <td>{{ $report->year }}</td>
                <td>{{ $report->year_start }}</td>
                <td>{{ $report->year_end }}</td>
                <td>{{ number_format($report->total_sales, 2) }}</td>
                <td>{{ $report->services }}</td>
                <td>{{ $report->employees }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
