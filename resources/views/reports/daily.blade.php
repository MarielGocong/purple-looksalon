<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Total Sales</th>
            <th>Services Rendered</th>
            <th>Employees Involved</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        <tr>
            <td>{{ $report->date }}</td>
            <td>{{ number_format($report->total_sales, 2) }}</td>
            <td>{{ $report->services }}</td>
            <td>{{ $report->employees }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('daily.report.pdf') }}" class="btn btn-primary" style="margin-bottom: 20px;">
    Download PDF
</a>
