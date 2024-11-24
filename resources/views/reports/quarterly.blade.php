<!DOCTYPE html>
<html>
<head>
    <title>Quarterly Sales Report</title>
</head>
<body>
    <h1>Quarterly Sales Report</h1>
    <a href="{{ route('quarterly.report.pdf') }}" class="btn btn-primary" style="margin-bottom: 20px;">Download PDF</a>

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Quarter</th>
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
                <td>{{ $report->quarter_label }}</td>
                <td>{{ $report->quarter_start }}</td>
                <td>{{ $report->quarter_end }}</td>
                <td>{{ number_format($report->total_sales, 2) }}</td>
                <td>{{ $report->services }}</td>
                <td>{{ $report->employees }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
